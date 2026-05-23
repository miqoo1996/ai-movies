<?php

namespace App\Console\Commands;

use App\Models\Episode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadEpisodeThumbs extends Command
{
    protected $signature = 'episodes:download-thumbs
                            {--limit=0 : Max episodes to process (0 = all pending)}';

    protected $description = 'Download and store episode thumbnail images locally';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');

        $query = Episode::whereNotNull('thumb')->whereNull('thumb_local');

        if ($limit > 0) {
            $query->limit($limit);
        }

        $episodes = $query->get();

        if ($episodes->isEmpty()) {
            $this->line('Nothing to download.');
            return self::SUCCESS;
        }

        $this->info("Downloading thumbnails for {$episodes->count()} episodes...");

        Storage::disk('public')->makeDirectory('episode-thumbs');

        $ok = 0;
        $fail = 0;

        foreach ($episodes as $ep) {
            try {
                $response = Http::timeout(20)->get($ep->thumb);

                if (! $response->successful()) {
                    $this->warn("Failed ({$response->status()}): episode #{$ep->id}");
                    $fail++;
                    continue;
                }

                $path = "episode-thumbs/{$ep->id}.jpg";
                Storage::disk('public')->put($path, $response->body());

                $ep->update(['thumb_local' => $path]);
                $ok++;
            } catch (\Throwable $e) {
                $this->warn("Error on episode #{$ep->id}: {$e->getMessage()}");
                $fail++;
            }
        }

        $this->info("Done. Downloaded: {$ok}, Failed: {$fail}.");

        return self::SUCCESS;
    }
}
