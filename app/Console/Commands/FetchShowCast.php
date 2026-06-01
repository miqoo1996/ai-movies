<?php

namespace App\Console\Commands;

use App\Models\Person;
use App\Models\Show;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchShowCast extends Command
{
    protected $signature = 'shows:fetch-cast
                            {--id=      : Fetch cast for a specific show by internal DB id}
                            {--slug=    : Fetch cast for a specific show by slug}
                            {--missing  : Only fetch shows that have no cast yet}
                            {--delay=300 : Milliseconds to sleep between TVMaze requests}';

    protected $description = 'Fetch cast & crew from TVMaze for shows';

    private const TVMAZE_BASE = 'https://api.tvmaze.com';

    public function handle(): int
    {
        if ($id = $this->option('id')) {
            $shows = Show::where('id', $id)->get();
        } elseif ($slug = $this->option('slug')) {
            $shows = Show::where('slug', $slug)->get();
        } elseif ($this->option('missing')) {
            $shows = Show::whereNull('tvmaze_id')
                ->orWhereDoesntHave('castMembers')
                ->orderBy('subscribers', 'desc')
                ->get();
        } else {
            $shows = Show::orderBy('subscribers', 'desc')->get();
        }

        $delay   = (int) $this->option('delay');
        $total   = $shows->count();
        $success = 0;
        $failed  = 0;
        $skipped = 0;

        $this->info("Fetching cast for {$total} show(s)...");

        foreach ($shows as $i => $show) {
            $this->line(sprintf('  [%d/%d] %s', $i + 1, $total, $show->getRawOriginal('title')));

            // Find or confirm TVMaze ID
            if (! $show->tvmaze_id) {
                $tvmazeId = $this->searchTvmaze($show);
                if (! $tvmazeId) {
                    $this->warn('    TVMaze: not found, skipping.');
                    $skipped++;
                    $this->maybeDelay($delay, $i, $total);
                    continue;
                }
                $show->update(['tvmaze_id' => $tvmazeId]);
                $this->line("    TVMaze ID: {$tvmazeId}");
            }

            $saved = $this->fetchAndSaveCast($show);
            if ($saved === null) {
                $this->warn('    Cast fetch failed, skipping.');
                $failed++;
            } else {
                $this->line("    Saved {$saved} cast member(s).");
                $success++;
            }

            $this->maybeDelay($delay, $i, $total);
        }

        $this->info("Done. Success: {$success} | Failed: {$failed} | Skipped: {$skipped}");
        return self::SUCCESS;
    }

    private function searchTvmaze(Show $show): ?int
    {
        $title = $show->getRawOriginal('turkish_title') ?: $show->getRawOriginal('original_title') ?: $show->getRawOriginal('title');

        // Strip parenthetical English translation if present
        $title = preg_replace('/\s*\([^)]+\)\s*$/', '', $title);

        try {
            $resp = Http::timeout(15)->get(self::TVMAZE_BASE . '/search/shows', ['q' => $title]);
            if (! $resp->successful()) return null;

            $results = $resp->json();
            if (empty($results)) return null;

            $year = $show->year;

            // Try to find best match: language=Turkish and premiered year matches
            foreach ($results as $result) {
                $s = $result['show'] ?? [];
                if (($s['language'] ?? '') !== 'Turkish') continue;
                if ($year && str_starts_with($s['premiered'] ?? '', (string) $year)) {
                    return $s['id'];
                }
            }

            // Fallback: first Turkish show
            foreach ($results as $result) {
                $s = $result['show'] ?? [];
                if (($s['language'] ?? '') === 'Turkish') {
                    return $s['id'];
                }
            }

            return null;
        } catch (\Exception $e) {
            $this->warn("    Search error: {$e->getMessage()}");
            return null;
        }
    }

    private function fetchAndSaveCast(Show $show): ?int
    {
        try {
            $resp = Http::timeout(15)->get(self::TVMAZE_BASE . "/shows/{$show->tvmaze_id}/cast");
            if (! $resp->successful()) return null;

            $castData = $resp->json();
            if (! is_array($castData)) return null;

            $saved = 0;
            foreach ($castData as $order => $entry) {
                $personData    = $entry['person'] ?? [];
                $characterData = $entry['character'] ?? [];

                if (empty($personData['id'])) continue;

                $person = Person::updateOrCreate(
                    ['tvmaze_id' => $personData['id']],
                    [
                        'name'     => $personData['name'] ?? 'Unknown',
                        'photo'    => $personData['image']['medium'] ?? null,
                        'gender'   => $personData['gender'] ?? null,
                        'birthday' => $personData['birthday'] ?? null,
                        'country'  => $personData['country']['name'] ?? null,
                    ]
                );

                $show->castMembers()->syncWithoutDetaching([
                    $person->id => [
                        'department'      => 'cast',
                        'role'            => null,
                        'character_name'  => $characterData['name'] ?? null,
                        'character_photo' => $characterData['image']['medium'] ?? null,
                        'sort_order'      => $order,
                    ],
                ]);

                $saved++;
            }

            return $saved;
        } catch (\Exception $e) {
            $this->warn("    Cast error: {$e->getMessage()}");
            return null;
        }
    }

    private function maybeDelay(int $ms, int $i, int $total): void
    {
        if ($ms > 0 && $i < $total - 1) {
            usleep($ms * 1000);
        }
    }
}
