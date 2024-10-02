<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat\Characters\Drivers;

use App\Services\OpenAI\Chat\Characters\AbstractCharacter;

readonly class KimiCharacter extends AbstractCharacter
{
    protected function boilerplate(): string
    {
        return __('openai.chat.characters.kimi');
    }
}
