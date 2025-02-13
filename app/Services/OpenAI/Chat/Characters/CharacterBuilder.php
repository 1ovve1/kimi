<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat\Characters;

use App\Data\OpenAI\Chat\CharacterData;
use App\Data\OpenAI\Chat\DialogMessageData;
use App\Services\OpenAI\Chat\Enums\ChatModelEnum;
use App\Services\OpenAI\Chat\Enums\DialogRolesEnum;

readonly class CharacterBuilder implements CharacterBuilderInterface
{
    public function __construct(
        public ChatModelEnum $modelsEnum,
        public CharacterData $characterData,
        private string $buffer = '',
    ) {}

    public function createRequestBody(DialogMessageData ...$dialogMessageData): array
    {
        return [
            'model' => $this->modelsEnum->value,
            'messages' => [
                new DialogMessageData("{$this->characterData->prompt}\n{$this->buffer}", DialogRolesEnum::SYSTEM),
                ...$dialogMessageData,
            ],
        ];
    }

    public function withInteractiveMode(): CharacterBuilderInterface
    {
        return new self($this->modelsEnum, $this->characterData, $this->buffer.__('openai.chat.prompts.interactive')."\n");
    }

    public function withMarkdownResponse(): CharacterBuilderInterface
    {
        return new self($this->modelsEnum, $this->characterData, $this->buffer.__('openai.chat.prompts.markdown')."\n");
    }

    public function withHtmlResponse(): CharacterBuilderInterface
    {
        return new self($this->modelsEnum, $this->characterData, $this->buffer.__('openai.chat.prompts.html')."\n");
    }

    public function withGodMode(): CharacterBuilderInterface
    {
        return new self($this->modelsEnum, $this->characterData, $this->buffer.__('openai.chat.prompts.godmode')."\n");
    }
}
