<?php

namespace App\Exceptions\Services\OpenAI\Tokenizer;

use App\Exceptions\CheckedException;

class TokensLimitException extends CheckedException
{
    protected string $messageFormat = 'Token limit (%s) exceed (%s)';

    public function __construct(int $max, int $actual)
    {
        parent::__construct($this->formatMessage($max, $actual));
    }
}
