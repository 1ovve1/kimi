<?php

namespace App\Exceptions\Repository\OpenAI\Character;

use App\Enums\Models\CharacterEnum;
use App\Exceptions\CheckedException;

class CharacterNotFoundException extends CheckedException
{
    protected string $messageFormat = "Character '%s' was not founded";

    public function __construct(CharacterEnum $characterEnum)
    {
        parent::__construct($this->formatMessage($characterEnum->value));
    }
}
