<?php

namespace App\Services;

interface AiServiceInterface
{
    public function prompt(string $prompt, ?string $system = null): string;

    public function json(string $prompt, ?string $system = null): array;
}
