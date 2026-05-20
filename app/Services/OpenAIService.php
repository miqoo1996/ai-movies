<?php

namespace App\Services;

use OpenAI;
use OpenAI\Client;

class OpenAIService
{
    private Client $client;
    private string $model;

    public function __construct()
    {
        $this->client = OpenAI::client(config('services.openai.key'));
        $this->model  = config('services.openai.model', 'gpt-4o-mini');
    }

    /**
     * Send a simple prompt and get a text response.
     */
    public function prompt(string $prompt, ?string $system = null, ?string $model = null): string
    {
        $messages = [];

        if ($system) {
            $messages[] = ['role' => 'system', 'content' => $system];
        }

        $messages[] = ['role' => 'user', 'content' => $prompt];

        $response = $this->client->chat()->create([
            'model'    => $model ?? $this->model,
            'messages' => $messages,
        ]);

        return $response->choices[0]->message->content ?? '';
    }

    /**
     * Send a conversation (array of ['role' => ..., 'content' => ...] messages).
     */
    public function chat(array $messages, ?string $model = null): string
    {
        $response = $this->client->chat()->create([
            'model'    => $model ?? $this->model,
            'messages' => $messages,
        ]);

        return $response->choices[0]->message->content ?? '';
    }

    /**
     * Return a structured JSON response decoded as an array.
     */
    public function json(string $prompt, ?string $system = null, ?string $model = null): array
    {
        $messages = [];

        if ($system) {
            $messages[] = ['role' => 'system', 'content' => $system];
        }

        $messages[] = ['role' => 'user', 'content' => $prompt];

        $response = $this->client->chat()->create([
            'model'           => $model ?? $this->model,
            'messages'        => $messages,
            'response_format' => ['type' => 'json_object'],
        ]);

        $content = $response->choices[0]->message->content ?? '{}';

        return json_decode($content, true) ?? [];
    }
}
