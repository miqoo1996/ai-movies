<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService implements AiServiceInterface
{
    private ?string $apiKey;
    private string $model;
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->model  = config('services.gemini.model', 'gemini-1.5-flash');
    }

    public function prompt(string $prompt, ?string $system = null): string
    {
        $this->ensureKey();

        $response = Http::timeout(60)
            ->post("{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}", $this->buildBody($prompt, $system));

        $this->throwIfError($response);

        return $response->json('candidates.0.content.parts.0.text') ?? '';
    }

    public function json(string $prompt, ?string $system = null): array
    {
        $this->ensureKey();

        $response = Http::timeout(60)
            ->post("{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}", $this->buildBody($prompt, $system, forceJson: true));

        $this->throwIfError($response);

        $text = $response->json('candidates.0.content.parts.0.text') ?? '';

        if ($text === '') {
            $finishReason = $response->json('candidates.0.finishReason');
            throw new \RuntimeException("Gemini returned empty text. finishReason: {$finishReason}");
        }

        // Strip markdown code fences Gemini sometimes wraps JSON in
        $text = preg_replace('/^```(?:json)?\s*/i', '', trim($text));
        $text = preg_replace('/\s*```$/',            '', $text);

        $decoded = json_decode($text, true);

        if ($decoded === null) {
            throw new \RuntimeException("Gemini returned invalid JSON: " . substr($text, 0, 200));
        }

        return $decoded;
    }

    private function throwIfError(\Illuminate\Http\Client\Response $response): void
    {
        if ($response->failed()) {
            $message = $response->json('error.message') ?? $response->body();
            $status  = $response->status();
            throw new \RuntimeException("Gemini API error {$status}: {$message}");
        }

        // Safety block — candidates array empty
        if ($response->json('candidates') === null && $response->json('promptFeedback.blockReason')) {
            $reason = $response->json('promptFeedback.blockReason');
            throw new \RuntimeException("Gemini blocked the prompt: {$reason}");
        }
    }

    private function ensureKey(): void
    {
        if (empty($this->apiKey)) {
            throw new \RuntimeException('GEMINI_KEY is not set in .env');
        }
    }

    private function buildBody(string $prompt, ?string $system, bool $forceJson = false): array
    {
        $body = [
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $prompt]]],
            ],
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 2048,
            ],
        ];

        if ($system) {
            $body['system_instruction'] = ['parts' => [['text' => $system]]];
        }

        if ($forceJson) {
            $body['generationConfig']['responseMimeType'] = 'application/json';
        }

        return $body;
    }
}
