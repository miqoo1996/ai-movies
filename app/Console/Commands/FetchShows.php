<?php

namespace App\Console\Commands;

use App\Models\Genre;
use App\Models\Show;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class FetchShows extends Command
{
    protected $signature = 'shows:fetch
                            {--page=1 : Page to start from}
                            {--all : Fetch all pages}';

    protected $description = 'Fetch and save shows from dizilah.com API';

    private const BASE_URL = 'https://dizilah.com/api/filter/shows';

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

            $response = $this->fetchPage($page);

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

    private function fetchPage(int $page): ?array
    {
        $url = self::BASE_URL . '?' . http_build_query(['page' => $page, 'include' => 'genres']);

        $process = new Process(['python3', $this->script, $url]);
        $process->setTimeout(60);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error(trim($process->getErrorOutput()) ?: "Process failed on page {$page}");
            return null;
        }

        $data = json_decode($process->getOutput(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Invalid JSON on page {$page}: " . json_last_error_msg());
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
            $count++;
        }

        return $count;
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
