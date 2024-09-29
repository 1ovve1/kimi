<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Tokenizer;

use App\Services\Abstract\AbstractService;
use App\Services\OpenAI\AIModelsEnum;
use Rajentrivedi\TokenizerX\TokenizerX;

class TokenizerService extends AbstractService implements TokenizerServiceInterface
{
    public function __construct(
        readonly AIModelsEnum $GPTModelsEnum
    ) {}

    public function count(string $prompt): int
    {
        return TokenizerX::count($prompt, $this->GPTModelsEnum->value);
    }
}
