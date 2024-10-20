<?php

namespace App\Data\Telegram\Chat;

use App\Data\Telegram\UserData;
use SergiX44\Nutgram\Telegram\Properties\ChatMemberStatus;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatMember;
use Spatie\LaravelData\Data;

class ChatUserData extends Data
{
    public function __construct(
        readonly ?int $id,
        readonly ChatMemberStatus $status,
        readonly UserData $user
    ) {}

    public static function fromNutgram(ChatMember $chatMember): self
    {
        return self::from([
            ...$chatMember->toArray(),
            'user' => UserData::fromNutgram($chatMember->user),
        ]);
    }
}
