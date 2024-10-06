<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\TelegramData;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Data\Telegram\Chat\Types\ChannelData;
use App\Data\Telegram\Chat\Types\GroupData;
use App\Data\Telegram\Chat\Types\PrivateData;
use App\Data\Telegram\Chat\Types\SupergroupData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\TelegramData\ReplyWasNotFoundedException;
use App\Repositories\Abstract\AbstractRepository;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ChatType;

class NutgramTelegramDataRepository extends AbstractRepository implements TelegramDataRepositoryInterface
{
    public function __construct(
        private readonly Nutgram $nutgram
    ) {}

    public function getMessage(): ChatMessageData
    {
        $message = $this->nutgram->message();

        return ChatMessageData::fromNutgram($message);
    }

    public function getReplyMessage(): ChatMessageData
    {
        $message = $this->nutgram->message()->reply_to_message ?? throw new ReplyWasNotFoundedException;

        return ChatMessageData::fromNutgram($message);
    }

    public function getChat(): ChatData
    {
        $chat = $this->nutgram->chat();

        return ChatData::fromNutgram($chat);
    }

    public function getUser(): UserData
    {
        $user = $this->nutgram->user();

        return UserData::fromNutgram($user);
    }

    public function getMe(): UserData
    {
        $user = $this->nutgram->getMe();

        return UserData::fromNutgram($user);
    }

    public function getUserReply(): UserData
    {
        $user = $this->nutgram->message()->reply_to_message?->from ?? throw new ReplyWasNotFoundedException();

        return UserData::fromNutgram($user);
    }
}
