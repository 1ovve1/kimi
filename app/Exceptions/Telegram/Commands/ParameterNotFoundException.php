<?php

namespace App\Exceptions\Telegram\Commands;

use App\Exceptions\UncheckedException;

class ParameterNotFoundException extends UncheckedException
{
    protected string $messageFormat = "Missing parameter '%s'";

    public function __construct(string $parameterName)
    {
        parent::__construct($this->formatMessage($parameterName));
    }
}
