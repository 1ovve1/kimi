<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Tokenizer;

use App\Exceptions\Services\OpenAI\Tokenizer\TokensLimitException;
use App\Services\Abstract\AbstractService;
use App\Services\OpenAI\AIModelsEnum;
use Illuminate\Support\Collection;
use Rajentrivedi\TokenizerX\TokenizerX;

class TokenizerService extends AbstractService implements TokenizerServiceInterface
{
    public function __construct(
        readonly AIModelsEnum $GPTModelsEnum,
    ) {}

    public function count(string|array|Collection $prompt): int
    {
        if ($prompt instanceof Collection) {
            $prompt = $prompt->toArray();
        }

        if (is_array($prompt)) {
            $prompt = implode(PHP_EOL, $prompt);
        }

        return TokenizerX::count($prompt, $this->GPTModelsEnum->value);
    }

    public function tryCount(string|array|Collection $prompt): int
    {
        $tokensCountLimit = $this->GPTModelsEnum->details()['limit'];
        $tokensCount = $this->count($prompt);

        if ($tokensCount > $tokensCountLimit) {
            throw new TokensLimitException($tokensCount, $tokensCountLimit);
        }

        return $tokensCount;
    }

    public function getModel(): AIModelsEnum
    {
        return $this->GPTModelsEnum;
    }
}
