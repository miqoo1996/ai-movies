<?php

namespace App\Console\Commands;

use App\Models\Show;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadPosters extends Command
{
    protected $signature = 'shows:download-posters
                            {--limit=0 : Max shows to process (0 = all pending)}';

    protected $description = 'Download and store poster images locally for shows that are missing them';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');

        $query = Show::whereNotNull('poster')->whereNull('poster_local');

        if ($limit > 0) {
            $query->limit($limit);
        }

        $shows = $query->get();

        if ($shows->isEmpty()) {
            $this->line('Nothing to download.');
            return self::SUCCESS;
        }

        $this->info("Downloading posters for {$shows->count()} shows...");

        foreach ($shows as $show) {
            try {
                $response = Http::timeout(20)->get($show->poster);

                if (! $response->successful()) {
                    $this->warn("Failed ({$response->status()}): {$show->slug}");
                    continue;
                }

                $path = "posters/{$show->slug}.jpg";
                Storage::disk('public')->put($path, $response->body());

                $show->update(['poster_local' => $path]);

                $this->line("Saved: {$show->slug}");
            } catch (\Throwable $e) {
                $this->warn("Error on {$show->slug}: {$e->getMessage()}");
            }
        }

        $this->info('Done.');

        return self::SUCCESS;
    }
}
