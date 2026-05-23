<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncShowsFromCsv extends Command
{
    protected $signature = 'shows:sync-csv
                            {file? : Path to CSV file (defaults to fully_processed_movies_shows.csv in project root)}
                            {--dry-run : Preview changes without writing to DB}';

    protected $description = 'Sync show columns from a CSV file into the shows table (matched by id)';

    public function handle(): int
    {
        $path = $this->argument('file') ?? base_path('fully_processed_movies_shows.csv');

        if (! file_exists($path)) {
            $this->error("File not found: {$path}");
            return self::FAILURE;
        }

        $handle = fopen($path, 'r');
        $headers = array_map('trim', fgetcsv($handle));

        // Columns we are allowed to update (never overwrite id, slug, timestamps)
        $allowed = [
            'ai_title', 'ai_turkish_title', 'ai_synopsis', 'title', 'synopsis',
            'status', 'network', 'runtime', 'premiered', 'year', 'rating',
            'subscribers', 'poster', 'poster_local', 'turkish_title',
            'original_title', 'external_id', 'hashid',
        ];

        $updateCols = array_values(array_intersect($headers, $allowed));

        if (empty($updateCols)) {
            $this->error('No updatable columns found in CSV headers: ' . implode(', ', $headers));
            fclose($handle);
            return self::FAILURE;
        }

        if (! in_array('id', $headers)) {
            $this->error('CSV must contain an "id" column to match rows.');
            fclose($handle);
            return self::FAILURE;
        }

        $this->info('CSV columns detected : ' . implode(', ', $headers));
        $this->info('Columns to update    : ' . implode(', ', $updateCols));

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN — no changes will be written.');
        }

        // Count total rows for the progress bar
        $total = 0;
        while (fgetcsv($handle) !== false) {
            $total++;
        }
        rewind($handle);
        fgetcsv($handle); // skip header again

        $updated = $skipped = $notFound = 0;

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($headers, $row);
            $id   = (int) $data['id'];

            // Only include non-empty values so we never blank-out existing data
            $payload = [];
            foreach ($updateCols as $col) {
                $val = $data[$col] ?? '';
                if ($val !== '') {
                    $payload[$col] = $val;
                }
            }

            if (empty($payload)) {
                $skipped++;
                $bar->advance();
                continue;
            }

            if (! DB::table('shows')->where('id', $id)->exists()) {
                $notFound++;
                $bar->advance();
                continue;
            }

            if (! $this->option('dry-run')) {
                DB::table('shows')->where('id', $id)->update($payload);
            }

            $updated++;
            $bar->advance();
        }

        $bar->finish();
        fclose($handle);

        $this->newLine(2);
        $this->table(
            ['Result', 'Count'],
            [
                ['Updated',             $updated],
                ['Skipped (empty row)', $skipped],
                ['Not found in DB',     $notFound],
            ]
        );

        return self::SUCCESS;
    }
}
