<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Tokenizer;

use App\Exceptions\Services\OpenAI\Tokenizer\TokensLimitException;
use App\Services\OpenAI\AIModelsEnum;
use Illuminate\Support\Collection;

interface TokenizerServiceInterface
{
    /**
     * @param  array<string>|string  $prompt
     * @return int - count of tokens in given $prompt
     */
    public function count(string|array|Collection $prompt): int;

    /**
     * @param  array<string>|string  $prompt
     *
     * @throws TokensLimitException
     */
    public function tryCount(string|array|Collection $prompt): int;

    /**
     * Return model instance
     */
    public function getModel(): AIModelsEnum;
}
