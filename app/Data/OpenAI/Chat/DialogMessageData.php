<?php

namespace App\Data\OpenAI\Chat;

use App\Data\Telegram\Chat\ChatMessageData;
use App\Services\OpenAI\Chat\Enums\DialogRolesEnum;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class DialogMessageData extends Data
{
    #[Computed]
    public readonly string $role;

    public function __construct(
        readonly string $content,
        DialogRolesEnum $role,
        readonly ?int $tokens_count = null
    ) {
        $this->role = $role->value;
    }

    public static function fromChatMessage(ChatMessageData $chatMessageData): self
    {
        return new self($chatMessageData->text, DialogRolesEnum::USER);
    }
}
