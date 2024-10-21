<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat\Characters;

use App\Data\OpenAI\Chat\CharacterData;
use App\Services\OpenAI\Chat\Enums\ChatModelEnum;

readonly class CharacterBuilderFactory
{
    public function __construct(
        public ChatModelEnum $modelEnum
    ) {}

    public function fromCharacterData(CharacterData $characterData): CharacterBuilderInterface
    {
        return new CharacterBuilder($this->modelEnum, $characterData);
    }

    public function makeDefault(): CharacterBuilderInterface
    {
        return new CharacterBuilder($this->modelEnum, CharacterData::makeDefault());
    }
}
