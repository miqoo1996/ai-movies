<?php

namespace App\Console\Commands;

use App\Models\Episode;
use App\Models\Genre;
use App\Models\Show;
use App\Models\ShowImage;
use App\Models\ShowStreamingSource;
use App\Models\ShowVideo;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

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

    private string $script;

    public function __construct()
    {
        parent::__construct();
        $this->script = base_path('scripts/fetch_page.py');
    }

    public function handle(): int
    {
        $page     = (int) $this->option('page');
        $fetchAll = $this->option('all');
        $lastPage = null;
        $total    = 0;

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

    private function fetchUrl(string $url, bool $withAuth = false): ?array
    {
        $headers = $withAuth ? self::AUTH_HEADERS : [];

        $args = ['python3', $this->script, $url];
        if ($headers) {
            $args[] = json_encode($headers);
        }

        $process = new Process($args);
        $process->setTimeout(60);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->warn(trim($process->getErrorOutput()) ?: "Process failed for: {$url}");
            return null;
        }

        $data = json_decode($process->getOutput(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->warn("Invalid JSON for {$url}: " . json_last_error_msg());
            return null;
        }

        return $data;
    }

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
        $url  = self::IMAGES_URL . '?' . http_build_query([
            'model'    => 'show',
            'model_id' => $show->external_id,
            'gallery'  => 'show_images',
        ]);

        $items = $this->fetchUrl($url, withAuth: true);

        if (! is_array($items)) {
            return;
        }

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
        $url   = self::VIDEOS_URL . '?' . http_build_query([
            'model'    => 'show',
            'model_id' => $show->external_id,
        ]);

        $items = $this->fetchUrl($url, withAuth: true);

        if (! is_array($items)) {
            return;
        }

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
        $url   = self::WTW_URL . '/' . $show->hashid . '?include=source';
        $items = $this->fetchUrl($url, withAuth: false);

        if (! is_array($items)) {
            return;
        }

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
        $url  = self::EPISODE_URL . '/' . $show->external_id . '/episodes/latest?sortBy=desc';
        $resp = $this->fetchUrl($url, withAuth: true);

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
                    'thumb'          => ($ep['thumb'] ?? null) === '/img/default-episode.webp' ? null : ($ep['thumb'] ?? null),
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
