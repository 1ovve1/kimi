<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\Traits\FormattedMessageTrait;
use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

abstract class CheckedException extends Exception
{
    use FormattedMessageTrait;

    #[Pure]
    public function __construct(?string $message = null, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?? $this->messageFormat, $code, $previous);
    }
}
