<?php

namespace App\Console\Commands;

use App\Models\Episode;
use App\Models\Show;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncTurkflixEpisodes extends Command
{
    protected $signature = 'turkflix:sync
                            {--dry-run : Preview matches without writing to the database}';

    protected $description = 'Sync new episodes from turk-flix.xyz for shows that already exist locally';

    private string $apiBase;
    private string $token;

    public function handle(): int
    {
        $this->apiBase = rtrim(env('TURKFLIX_API_URL', 'https://turk-flix.xyz/api'), '/');

        $this->info('Logging in to turk-flix.xyz…');
        if (! $this->login()) {
            $this->error('Login failed. Check TURKFLIX_EMAIL / TURKFLIX_PASSWORD in .env.');
            return self::FAILURE;
        }
        $this->line('  OK');

        $isDry      = $this->option('dry-run');
        $showMap    = $this->buildShowMap();
        $page       = 1;
        $lastPage   = null;
        $totalAdded = 0;
        $matched    = 0;

        do {
            $data = $this->fetchPage($page);

            if ($data === null) {
                $this->error("Failed to fetch page {$page}. Stopping.");
                return self::FAILURE;
            }

            $lastPage = $lastPage ?? ($data['movies']['last_page'] ?? 1);
            $movies   = $data['movies']['data'] ?? [];

            $this->line("Page {$page}/{$lastPage} — " . count($movies) . " movies");

            foreach ($movies as $movie) {
                $show = $this->findShow($movie, $showMap);

                if (! $show) {
                    continue;
                }

                // Persist the mapping so future runs skip the title-match step
                if (! $show->getRawOriginal('turkflix_id') && ! $isDry) {
                    $show->updateQuietly(['turkflix_id' => $movie['id']]);
                    // Update the in-memory map too
                    $showMap['id:' . $movie['id']] = $show;
                }

                $newEps = $this->syncEpisodes($show, $movie['items'] ?? [], $isDry);

                if ($newEps > 0 || $isDry) {
                    $matched++;
                    $label = $isDry ? "[dry] would add {$newEps}" : "+{$newEps}";
                    $this->line("  ✓ {$show->getRawOriginal('title')} → {$label} episode(s)");
                    $totalAdded += $newEps;
                }
            }

            $page++;
        } while ($page <= $lastPage);

        $suffix = $isDry ? ' (dry run — nothing written)' : '';
        $this->info("Done. {$totalAdded} new episode(s) added across {$matched} show(s){$suffix}.");

        return self::SUCCESS;
    }

    // ── Auth ─────────────────────────────────────────────────────────────

    private function login(): bool
    {
        $resp = Http::post($this->apiBase . '/login', [
            'email'    => env('TURKFLIX_EMAIL'),
            'password' => env('TURKFLIX_PASSWORD'),
        ]);

        if (! $resp->successful() || ! $resp->json('token')) {
            return false;
        }

        $this->token = $resp->json('token');
        return true;
    }

    // ── Show matching ────────────────────────────────────────────────────

    private function buildShowMap(): array
    {
        $map = [];

        Show::select(['id', 'external_id', 'turkflix_id', 'title', 'original_title', 'turkish_title', 'ai_title', 'ai_turkish_title'])
            ->get()
            ->each(function (Show $show) use (&$map) {
                // Index by turkflix_id for fast lookup on repeat runs
                if ($tid = $show->getRawOriginal('turkflix_id')) {
                    $map['id:' . $tid] = $show;
                }

                // Index by every title variant we have
                foreach (['title', 'original_title', 'turkish_title', 'ai_title', 'ai_turkish_title'] as $field) {
                    $val = $show->getRawOriginal($field);
                    if ($val) {
                        $map[$this->norm($val)] = $show;
                    }
                }
            });

        return $map;
    }

    private function findShow(array $movie, array $showMap): ?Show
    {
        // 1. Already matched in a previous run
        if (isset($showMap['id:' . $movie['id']])) {
            return $showMap['id:' . $movie['id']];
        }

        // 2. Title matching — turkflix titles look like:
        //    "BIZE BI'SEY OLMAZ (NOTHING HAPPENS TO US)"
        $raw      = $movie['title'];
        $variants = [$raw];

        // Part before the parenthesis (usually the Turkish title)
        if (preg_match('/^(.+?)\s*\(/', $raw, $m)) {
            $variants[] = trim($m[1]);
        }
        // Part inside the parenthesis (usually the English title)
        if (preg_match('/\((.+?)\)/', $raw, $m)) {
            $variants[] = trim($m[1]);
        }

        foreach ($variants as $v) {
            $key = $this->norm($v);
            if (isset($showMap[$key])) {
                return $showMap[$key];
            }
        }

        return null;
    }

    private function norm(string $title): string
    {
        // Transliterate Turkish characters to ASCII equivalents so that
        // "Kiralik Aşk" and "KIRALIK ASK" both normalise to "kiralik ask"
        $title = strtr($title, [
            'ş' => 's', 'Ş' => 's',
            'ğ' => 'g', 'Ğ' => 'g',
            'ı' => 'i', 'İ' => 'i',
            'ö' => 'o', 'Ö' => 'o',
            'ü' => 'u', 'Ü' => 'u',
            'ç' => 'c', 'Ç' => 'c',
        ]);
        $title = mb_strtolower($title);
        // Replace word-joining punctuation with a space so that
        // "Fatmagül'ün" → "fatmagul un" and "Aşk-ı" → "ask i"
        $title = preg_replace("/['\-\u{2019}\u{2018}]/u", ' ', $title);
        $title = preg_replace('/[^a-z0-9\s]/u', '', $title);
        $title = preg_replace('/\s+/', ' ', trim($title));
        return $title;
    }

    // ── Episode sync ─────────────────────────────────────────────────────

    private function syncEpisodes(Show $show, array $items, bool $dry): int
    {
        $added = 0;

        foreach ($items as $item) {
            // Skip if we've already stored this turkflix episode
            if (Episode::where('turkflix_item_id', $item['id'])->exists()) {
                continue;
            }

            $epNum = $this->extractEpisodeNumber($item['title'] ?? '');

            // Also skip if the show already has an episode with the same number in season 1
            // (it may have come from the primary dizilah source)
            if ($epNum !== null && Episode::where('show_id', $show->id)->where('season_number', 1)->where('episode_number', $epNum)->exists()) {
                continue;
            }

            if (! $dry) {
                Episode::create([
                    'show_id'          => $show->id,
                    'turkflix_item_id' => $item['id'],
                    'season_number'    => 1,
                    'episode_number'   => $epNum,
                    'has_aired'        => true,
                    'airs_on'          => isset($item['created_at'])
                                            ? Carbon::parse($item['created_at'])->toDateString()
                                            : null,
                ]);
            }

            $added++;
        }

        return $added;
    }

    private function extractEpisodeNumber(string $title): ?int
    {
        // "EPISODE 1", "Episode 12", "EP. 3", "Bölüm 5" etc.
        if (preg_match('/\b(\d+)\b/', $title, $m)) {
            return (int) $m[1];
        }
        return null;
    }

    // ── HTTP ─────────────────────────────────────────────────────────────

    private function fetchPage(int $page): ?array
    {
        $resp = Http::withToken($this->token)
            ->timeout(30)
            ->get($this->apiBase . '/movies/list', ['page' => $page]);

        if (! $resp->successful()) {
            $this->warn("HTTP {$resp->status()} on page {$page}");
            return null;
        }

        return $resp->json();
    }
}
