<?php

namespace App\Console\Commands;

use App\Models\Show;
use App\Models\ShowImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class FetchShowImages extends Command
{
    protected $signature = 'shows:fetch-images
                            {--id= : Fetch images for a specific show by internal DB id}
                            {--slug= : Fetch images for a specific show by slug}
                            {--delay=500 : Milliseconds to sleep between API requests}
                            {--no-download : Skip downloading files locally}';

    protected $description = 'Fetch and download gallery images for shows from dizilah.com';

    private const IMAGES_URL   = 'https://dizilah.com/api/v1/images';
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
        $delayMs    = (int) $this->option('delay');
        $noDownload = $this->option('no-download');

        if ($id = $this->option('id')) {
            $shows = Show::where('id', $id)->get();
        } elseif ($slug = $this->option('slug')) {
            $shows = Show::where('slug', $slug)->get();
        } else {
            $shows = Show::orderBy('id')->get();
        }

        $total      = $shows->count();
        $savedMeta  = 0;
        $savedFiles = 0;
        $empty      = 0;
        $failed     = 0;

        $this->info("Fetching images for {$total} show(s)...");

        foreach ($shows as $i => $show) {
            $this->line(sprintf('  [%d/%d] %s (ext_id=%d)', $i + 1, $total, $show->title, $show->external_id));

            $url   = self::IMAGES_URL . '?' . http_build_query([
                'model'    => 'show',
                'model_id' => $show->external_id,
                'gallery'  => 'show_images',
            ]);
            $items = $this->fetchWithRetry($url);

            if ($items === null) {
                $this->warn('    Failed after retries, skipping.');
                $failed++;
            } elseif (empty($items)) {
                $empty++;
            } else {
                foreach ($items as $img) {
                    $localPath  = null;
                    $localThumb = null;

                    if (! $noDownload) {
                        $localPath  = $this->downloadFile($img['url'],   $show->external_id);
                        $localThumb = isset($img['thumb'])
                            ? $this->downloadFile($img['thumb'], $show->external_id, 'thumb')
                            : null;
                        if ($localPath) $savedFiles++;
                    }

                    ShowImage::updateOrCreate(
                        ['external_id' => $img['id']],
                        [
                            'show_id'    => $show->id,
                            'hashid'     => $img['hashid'],
                            'url'        => $img['url'],
                            'local_path' => $localPath,
                            'thumb'      => $img['thumb'] ?? null,
                            'local_thumb' => $localThumb,
                            'width'      => $img['width'] ?? null,
                            'height'     => $img['height'] ?? null,
                            'mime_type'  => $img['mimeType'] ?? null,
                            'collection' => $img['collection'] ?? null,
                        ]
                    );
                    $savedMeta++;
                }
                $this->line("    Saved {$savedMeta} images" . ($noDownload ? '' : " ({$savedFiles} files downloaded)") . '.');
            }

            if ($delayMs > 0 && $i < $total - 1) {
                usleep($delayMs * 1000);
            }
        }

        $this->info("Done. Meta: {$savedMeta} | Files: {$savedFiles} | Empty: {$empty} | Failed: {$failed}");

        return self::SUCCESS;
    }

    private function downloadFile(string $remoteUrl, int $showExtId, string $prefix = ''): ?string
    {
        $filename  = ($prefix ? $prefix . '-' : '') . basename(parse_url($remoteUrl, PHP_URL_PATH));
        $dir       = "show-images/{$showExtId}";
        $storagePath = "{$dir}/{$filename}";

        // Skip if already downloaded
        if (Storage::disk('public')->exists($storagePath)) {
            return 'storage/' . $storagePath;
        }

        try {
            $response = Http::timeout(30)->get($remoteUrl);

            if ($response->successful()) {
                Storage::disk('public')->put($storagePath, $response->body());
                return 'storage/' . $storagePath;
            }
        } catch (\Exception $e) {
            $this->warn("    Download failed for {$remoteUrl}: {$e->getMessage()}");
        }

        return null;
    }

    private function fetchWithRetry(string $url, int $maxRetries = 3): ?array
    {
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            $args    = ['python3', $this->script, $url, json_encode(self::AUTH_HEADERS)];
            $process = new Process($args);
            $process->setTimeout(60);
            $process->run();

            if (! $process->isSuccessful()) {
                $err = trim($process->getErrorOutput());
                if (str_contains($err, '429')) {
                    $this->warn("    Rate limited (attempt {$attempt}/{$maxRetries}), waiting 10s...");
                    sleep(10);
                    continue;
                }
                $this->warn("    Error: {$err}");
                return null;
            }

            $data = json_decode($process->getOutput(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->warn('    Invalid JSON: ' . json_last_error_msg());
                return null;
            }

            return $data;
        }

        return null;
    }
}
