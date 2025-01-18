<?php

namespace App\Data\Telegram\Chat;

use DateTimeInterface;
use Illuminate\Support\Carbon;
use SergiX44\Nutgram\Telegram\Types\Message\Message;
use Spatie\LaravelData\Data;

class ChatMessageData extends Data
{
    public function __construct(
        readonly ?int $id,
        readonly int $tg_id,
        readonly DateTimeInterface $created_at,
        readonly string $text = '',
    ) {}

    public static function fromNutgram(Message $message): self
    {
        return self::from([
            ...$message->toArray(),
            'id' => null,
            'tg_id' => $message->message_id,
            'created_at' => Carbon::createFromTimestampUTC($message->date),
        ]);
    }
}
