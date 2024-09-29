<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Tokenizer;

interface TokenizerServiceInterface
{
    /**
     * @return int - count of tokens in given $prompt
     */
    public function count(string $prompt): int;
}
