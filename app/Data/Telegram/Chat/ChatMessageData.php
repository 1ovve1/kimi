<?php

namespace App\Data\Telegram\Chat;

use SergiX44\Nutgram\Telegram\Types\Message\Message;
use Spatie\LaravelData\Data;

class ChatMessageData extends Data
{
    public function __construct(
        readonly ?int $id,
        readonly int $tg_id,
        readonly string $text = ''
    ) {}

    public static function fromNutgram(Message $message): self
    {
        return self::from([
            ...$message->toArray(),
            'id' => null,
            'tg_id' => $message->message_id,
        ]);
    }
}
