<?php

namespace App\Console\Commands;

use App\Models\Episode;
use App\Models\Genre;
use App\Models\Show;
use App\Models\ShowImage;
use App\Models\ShowStreamingSource;
use App\Models\ShowVideo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchShows extends Command
{
    protected $signature = 'shows:fetch
                            {--page=1 : Page to start from}
                            {--all : Fetch all pages}';

    protected $description = 'Fetch and save shows from dizilah.com API';

    private const BASE_URL    = 'https://dizilah.com/api/filter/shows';
    private const IMAGES_URL  = 'https://dizilah.com/api/v1/images';
    private const VIDEOS_URL  = 'https://dizilah.com/api/v1/videos';
    private const WTW_URL     = 'https://dizilah.com/api/wheretowatch';
    private const EPISODE_URL = 'https://dizilah.com/api/show';

    private const AUTH_HEADERS = [
        'X-Dizilah-Key'   => '9cahda6EeP',
        'X-Dizilah-Token' => 'Yei6OhRu3uu7aza',
    ];

    // Mirrors what cloudscraper sends — Cloudflare trusts this profile
    private const USER_AGENT = 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.84 Safari/537.36';

    public function handle(): int
    {
        $page     = (int) $this->option('page');
        $fetchAll = $this->option('all');
        $lastPage = null;
        $total    = 0;

        $bypass = $this->detectBypassMethod();
        $this->info("Bypass method: {$bypass}");
        $this->info("Starting fetch from page {$page}" . ($fetchAll ? ' (all pages)' : '') . '...');

        do {
            $this->line("  Fetching page {$page}" . ($lastPage ? "/{$lastPage}" : '') . '...');

            $response = $this->fetchUrl(
                self::BASE_URL . '?' . http_build_query(['page' => $page, 'include' => 'genres'])
            );

            if ($response === null) {
                $this->error("Failed to fetch page {$page}. Stopping.");
                return self::FAILURE;
            }

            $data     = $response['data'] ?? [];
            $lastPage = $lastPage ?? ($response['meta']['pagination']['total_pages'] ?? 1);

            if (empty($data)) {
                $this->warn('No data returned on this page.');
                break;
            }

            $saved = $this->saveShows($data);
            $total += $saved;
            $this->line("  Saved {$saved} shows (total: {$total}).");

            $page++;
        } while ($fetchAll && $page <= $lastPage);

        $this->info("Done. Total shows saved/updated: {$total}.");

        return self::SUCCESS;
    }

    // ── Bypass detection ─────────────────────────────────────────────────────

    private function detectBypassMethod(): string
    {
        if (config('services.flaresolverr.url')) {
            return 'flaresolverr';
        }

        if (config('services.dizilah.cf_clearance')) {
            return 'cf_clearance cookie';
        }

        return 'direct (no bypass — may hit 403)';
    }

    // ── HTTP fetching ─────────────────────────────────────────────────────────

    private function fetchUrl(string $url, bool $withAuth = false): ?array
    {
        $extraHeaders = $withAuth ? self::AUTH_HEADERS : [];

        // 1. Try Flaresolverr
        if ($flaresolvrrUrl = config('services.flaresolverr.url')) {
            return $this->fetchViaFlaresolverr($url, $flaresolvrrUrl, $extraHeaders);
        }

        // 2. Try direct PHP request (with cf_clearance cookie if available)
        return $this->fetchDirect($url, $extraHeaders);
    }

    private function fetchViaFlaresolverr(string $url, string $solverrUrl, array $extraHeaders = []): ?array
    {
        try {
            $response = Http::timeout(120)->post(rtrim($solverrUrl, '/') . '/v1', [
                'cmd'        => 'request.get',
                'url'        => $url,
                'maxTimeout' => 60000,
            ]);

            if ($response->failed()) {
                $this->warn("Flaresolverr error {$response->status()}: " . $response->body());
                return null;
            }

            if ($response->json('status') !== 'ok') {
                $this->warn('Flaresolverr returned non-ok: ' . $response->json('message'));
                return null;
            }

            $body = $response->json('solution.response');

            // Flaresolverr returns HTML — but for JSON API endpoints the body is JSON text
            $data = json_decode($body, true);

            if ($data === null) {
                $this->warn("Flaresolverr: non-JSON response for {$url}");
                return null;
            }

            // If we got extra auth headers, re-fetch directly using the cf_clearance cookie
            // that Flaresolverr solved for us
            if ($extraHeaders) {
                $cfCookie = collect($response->json('solution.cookies', []))
                    ->firstWhere('name', 'cf_clearance');

                if ($cfCookie) {
                    return $this->fetchDirect($url, $extraHeaders, $cfCookie['value']);
                }
            }

            return $data;

        } catch (\Throwable $e) {
            $this->warn("Flaresolverr exception: " . $e->getMessage());
            return null;
        }
    }

    private function fetchDirect(string $url, array $extraHeaders = [], ?string $cfClearance = null): ?array
    {
        $headers = array_merge([
            'User-Agent'      => self::USER_AGENT,
            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept-Encoding' => 'gzip, deflate',
        ], $extraHeaders);

        $http = Http::withHeaders($headers)->timeout(60);

        $clearance = $cfClearance ?? config('services.dizilah.cf_clearance');
        if ($clearance) {
            $http = $http->withCookies(['cf_clearance' => $clearance], 'dizilah.com');
        }

        try {
            $response = $http->get($url);

            if ($response->status() === 403) {
                $this->warn("403 Cloudflare block on {$url}.");
                $this->warn('Fix: set FLARESOLVERR_URL in .env, or update DIZILAH_CF_CLEARANCE manually.');
                return null;
            }

            if ($response->failed()) {
                $this->warn("HTTP {$response->status()} for: {$url}");
                return null;
            }

            $data = $response->json();

            if ($data === null) {
                $this->warn("Invalid JSON for {$url}");
                return null;
            }

            return $data;

        } catch (\Throwable $e) {
            $this->warn("Request failed for {$url}: " . $e->getMessage());
            return null;
        }
    }

    // ── Data persistence ──────────────────────────────────────────────────────

    private function saveShows(array $items): int
    {
        $count = 0;

        foreach ($items as $item) {
            $genreIds = $this->upsertGenres($item['genres']['data'] ?? []);

            $show = Show::updateOrCreate(
                ['external_id' => $item['id']],
                [
                    'hashid'         => $item['hashid'],
                    'title'          => $item['title'],
                    'original_title' => $item['original_title'] ?? null,
                    'turkish_title'  => $item['turkish_title'] ?? null,
                    'slug'           => $item['slug'],
                    'status'         => $item['status'] ?? null,
                    'network'        => $item['network'] ?? null,
                    'runtime'        => isset($item['runtime']) ? (int) $item['runtime'] : null,
                    'premiered'      => $item['premiered'] ?? null,
                    'year'           => isset($item['year']) ? (int) $item['year'] : null,
                    'synopsis'       => $item['synopsis'] ?? null,
                    'poster'         => $item['images']['poster'] ?? null,
                    'rating'         => isset($item['rating']) && $item['rating'] > 0 ? $item['rating'] : null,
                    'subscribers'    => $item['subscribers'] ?? null,
                ]
            );

            $show->genres()->sync($genreIds);

            $this->fetchImages($show);
            $this->fetchVideos($show);
            $this->fetchWhereToWatch($show);
            $this->fetchEpisodes($show);

            $count++;
        }

        return $count;
    }

    private function fetchImages(Show $show): void
    {
        $items = $this->fetchUrl(
            self::IMAGES_URL . '?' . http_build_query([
                'model'    => 'show',
                'model_id' => $show->external_id,
                'gallery'  => 'show_images',
            ]),
            withAuth: true
        );

        if (! is_array($items)) return;

        foreach ($items as $img) {
            ShowImage::updateOrCreate(
                ['external_id' => $img['id']],
                [
                    'show_id'    => $show->id,
                    'hashid'     => $img['hashid'],
                    'url'        => $img['url'],
                    'thumb'      => $img['thumb'] ?? null,
                    'width'      => $img['width'] ?? null,
                    'height'     => $img['height'] ?? null,
                    'mime_type'  => $img['mimeType'] ?? null,
                    'collection' => $img['collection'] ?? null,
                ]
            );
        }
    }

    private function fetchVideos(Show $show): void
    {
        $items = $this->fetchUrl(
            self::VIDEOS_URL . '?' . http_build_query([
                'model'    => 'show',
                'model_id' => $show->external_id,
            ]),
            withAuth: true
        );

        if (! is_array($items)) return;

        foreach ($items as $vid) {
            ShowVideo::updateOrCreate(
                ['external_id' => $vid['id']],
                [
                    'show_id'     => $show->id,
                    'hashid'      => $vid['hashid'],
                    'url'         => $vid['url'],
                    'poster'      => $vid['poster'] ?? null,
                    'title'       => $vid['title'] ?? null,
                    'description' => $vid['description'] ?? null,
                    'playable'    => $vid['playable'] ?? true,
                ]
            );
        }
    }

    private function fetchWhereToWatch(Show $show): void
    {
        $items = $this->fetchUrl(
            self::WTW_URL . '/' . $show->hashid . '?include=source'
        );

        if (! is_array($items)) return;

        foreach ($items as $entry) {
            $source = $entry['source'] ?? [];

            ShowStreamingSource::updateOrCreate(
                ['external_id' => $entry['id']],
                [
                    'show_id'        => $show->id,
                    'hashid'         => $entry['hashid'],
                    'type'           => $entry['type'] ?? null,
                    'lang'           => $entry['lang'] ?? [],
                    'url'            => $entry['url'],
                    'source_name'    => $source['name'] ?? null,
                    'source_image'   => $source['image'] ?? null,
                    'source_slug'    => $source['slug'] ?? null,
                    'source_premium' => $source['premium'] ?? false,
                ]
            );
        }
    }

    private function fetchEpisodes(Show $show): void
    {
        $resp  = $this->fetchUrl(
            self::EPISODE_URL . '/' . $show->external_id . '/episodes/latest?sortBy=desc',
            withAuth: true
        );

        $items = $resp['data'] ?? (is_array($resp) ? $resp : []);

        foreach ($items as $ep) {
            Episode::updateOrCreate(
                ['external_id' => $ep['id']],
                [
                    'show_id'        => $show->id,
                    'hashid'         => $ep['hashid'],
                    'season_number'  => $ep['season_number'] ?? null,
                    'episode_number' => $ep['episode_number'] ?? null,
                    'shortcode'      => $ep['shortcode'] ?? null,
                    'airs_on'        => $ep['airs_on'] ?? null,
                    'has_aired'      => $ep['has_aired'] ?? false,
                    'season_finale'  => $ep['season_finale'] ?? false,
                    'thumb'          => ($ep['thumb'] ?? null) === '/img/default-episode.webp'
                                        ? null
                                        : ($ep['thumb'] ?? null),
                ]
            );
        }
    }

    private function upsertGenres(array $genres): array
    {
        $ids = [];

        foreach ($genres as $genre) {
            $model = Genre::updateOrCreate(
                ['external_id' => $genre['id']],
                [
                    'hashid' => $genre['hashid'],
                    'name'   => $genre['name'],
                    'slug'   => $genre['slug'],
                ]
            );

            $ids[] = $model->id;
        }

        return $ids;
    }
}
