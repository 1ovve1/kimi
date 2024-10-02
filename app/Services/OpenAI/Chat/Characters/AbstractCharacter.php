<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat\Characters;

use App\Data\OpenAI\Chat\DialogMessageData;
use App\Services\OpenAI\Chat\Enums\ChatModelEnum;
use App\Services\OpenAI\Chat\Enums\DialogRolesEnum;

abstract readonly class AbstractCharacter implements CharacterInterface
{
    public function __construct(
        public ChatModelEnum $modelsEnum
    ) {}

    public function createRequestBody(DialogMessageData ...$dialogMessageData): array
    {
        return [
            'model' => $this->modelsEnum->value,
            'messages' => [
                new DialogMessageData($this->boilerplate(), DialogRolesEnum::SYSTEM),
                ...$dialogMessageData,
            ],
        ];
    }

    abstract protected function boilerplate(): string;
}
