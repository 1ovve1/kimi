<?php

namespace App\Exceptions\Services\OpenAI\Tokenizer;

use App\Exceptions\CheckedException;
use Exception;
use Throwable;

class TokensLimitException extends CheckedException
{
    protected string $messageFormat = "Token limit (%s) exceed (%s)";

    public function __construct(int $max, int $actual)
    {
        parent::__construct($this->formatMessage($max, $actual));
    }
}
