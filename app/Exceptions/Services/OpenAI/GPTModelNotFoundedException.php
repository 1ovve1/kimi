<?php

namespace App\Exceptions\Services\OpenAI;

use App\Exceptions\UncheckedException;

class GPTModelNotFoundedException extends UncheckedException
{
    protected string $messageFormat = "'%s' model was not founded in config/gpt.php";

    public function __construct(string $dryModelName)
    {
        parent::__construct($this->formatMessage($dryModelName));
    }
}
