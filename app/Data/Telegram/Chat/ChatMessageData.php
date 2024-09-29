<?php

namespace App\Data\Telegram\Chat;

use SergiX44\Nutgram\Telegram\Types\Message\Message;
use Spatie\LaravelData\Data;

class ChatMessageData extends Data
{
    public function __construct(
        readonly int $id,
        readonly string $text
    ) {}

    public static function fromNutgramMessage(Message $message): self
    {
        return self::from([
            ...$message->toArray(),
            'id' => $message->message_id,
        ]);
    }
}
