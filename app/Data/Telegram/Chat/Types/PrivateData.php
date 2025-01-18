<?php

namespace App\Data\Telegram\Chat\Types;

use SergiX44\Nutgram\Telegram\Properties\ChatType;
use SergiX44\Nutgram\Telegram\Types\Chat\Chat;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class PrivateData extends Data
{
    #[Computed]
    public readonly ChatType $type;

    public function __construct(
        readonly ?int $id,
        readonly int $tg_id,
        readonly string $first_name,
        readonly ?string $last_name = null,
        readonly ?string $username = null,
        readonly ?string $language_code = null,
        readonly ?bool $is_premium = null,
        readonly bool $is_bot = false,
    ) {
        $this->type = ChatType::PRIVATE;
    }

    public static function fromNutgram(Chat $chat): self
    {
        return self::from([
            ...$chat->toArray(),
            'id' => null,
            'tg_id' => $chat->id,
        ]);
    }
}
