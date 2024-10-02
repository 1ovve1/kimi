<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat\Characters;

use App\Services\OpenAI\Chat\Characters\Drivers\KimiCharacter;
use App\Services\OpenAI\Chat\Enums\ChatModelEnum;

readonly class CharactersFactory
{
    public function __construct(
        public ChatModelEnum $modelsEnum
    ) {}

    public function createKimi(): CharacterInterface
    {
        return new KimiCharacter($this->modelsEnum);
    }
}
