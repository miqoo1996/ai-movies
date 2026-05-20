<?php

namespace App\Console\Commands;

use App\Models\Show;
use App\Services\OpenAIService;
use Illuminate\Console\Command;

class EnrichShows extends Command
{
    protected $signature = 'shows:enrich
                            {--limit=0 : Max shows to process (0 = all pending)}
                            {--delay=0 : Seconds to wait between OpenAI calls (use 20 for free-tier keys)}';

    protected $description = 'Generate AI title + synopsis for shows';

    public function handle(OpenAIService $ai): int
    {
        $limit = (int) $this->option('limit');
        $delay = (int) $this->option('delay');

        $this->generateAiContent($ai, $limit, $delay);

        return self::SUCCESS;
    }

    private function generateAiContent(OpenAIService $ai, int $limit, int $delay = 0): void
    {
        $query = Show::where(fn ($q) => $q
            ->whereNull('ai_title')
            ->orWhereNull('ai_turkish_title')
            ->orWhereNull('ai_synopsis')
        );

        if ($limit > 0) {
            $query->limit($limit);
        }

        $shows = $query->with('genres')->get();

        if ($shows->isEmpty()) {
            $this->line('[ai] Nothing to generate.');
            return;
        }

        $this->info("[ai] Generating AI content for {$shows->count()} shows...");

        foreach ($shows as $show) {
            $genres  = $show->genres->pluck('name')->join(', ');
            $details = implode(' | ', array_filter([
                $show->year    ? "Year: {$show->year}"       : null,
                $show->network ? "Network: {$show->network}" : null,
                $show->status  ? "Status: {$show->status}"   : null,
                $genres        ? "Genres: {$genres}"         : null,
            ]));

            $prompt = <<<PROMPT
You are given a Turkish TV show. Return a JSON object with exactly three keys:
- "ai_title": a clean, appealing English title (short, do not just transliterate the Turkish)
- "ai_turkish_title": a clean, natural Turkish title (short, without any English parts)
- "ai_synopsis": an engaging synopsis of 2–3 short paragraphs for international audiences

Show details:
Title: {$show->title}
Turkish title: {$show->turkish_title}
{$details}
Original synopsis: {$show->synopsis}

Do not invent plot details not implied by the original synopsis.
PROMPT;

            $result = $this->callWithBackoff(
                fn () => $ai->json($prompt, 'You are a professional TV content writer.')
            );

            if (empty($result)) {
                $this->warn("[ai] Skipped after retries: {$show->slug}");
                continue;
            }

            $show->update([
                'ai_title'         => $result['ai_title']         ?? null,
                'ai_turkish_title' => $result['ai_turkish_title'] ?? null,
                'ai_synopsis'      => $result['ai_synopsis']      ?? null,
            ]);

            $this->line("[ai] Generated: {$show->slug}");

            if ($delay > 0) {
                sleep($delay);
            }
        }

        $this->info('[ai] Done.');
    }

    private function callWithBackoff(callable $fn, int $maxRetries = 4): mixed
    {
        $delay = 5;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                return $fn();
            } catch (\Throwable $e) {
                $isRateLimit = str_contains($e->getMessage(), 'rate limit')
                    || str_contains($e->getMessage(), '429');

                if (! $isRateLimit || $attempt === $maxRetries) {
                    $this->warn("  Attempt {$attempt} failed: {$e->getMessage()}");
                    return null;
                }

                $this->line("  Rate limited, waiting {$delay}s (attempt {$attempt}/{$maxRetries})...");
                sleep($delay);
                $delay *= 2;
            }
        }

        return null;
    }
}
