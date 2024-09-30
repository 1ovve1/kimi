<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\Chat;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\Types\ChannelData;
use App\Data\Telegram\Chat\Types\GroupData;
use App\Data\Telegram\Chat\Types\PrivateData;
use App\Data\Telegram\Chat\Types\SupergroupData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Models\Channel;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Group;
use App\Models\Supergroup;
use App\Models\User;
use App\Repositories\Abstract\AbstractRepository;
use RuntimeException;
use SergiX44\Nutgram\Telegram\Properties\ChatType;

class ChatRepository extends AbstractRepository implements ChatRepositoryInterface
{
    public function save(ChatData $chatData): ChatData
    {
        $chat = Chat::with('target')->find($chatData->id);

        if ($chat === null) {
            return $this->create($chatData);
        }

        return ChatData::from($chat);
    }

    public function create(ChatData $chatData): ChatData
    {
        $targetData = match ($chatData->target->type) {
            ChatType::PRIVATE => $this->createPrivate($chatData->target),
            ChatType::GROUP => $this->createGroup($chatData->target),
            ChatType::SUPERGROUP => $this->createSupergroup($chatData->target),
            ChatType::CHANNEL => $this->createChannel($chatData->target),
            ChatType::SENDER => throw new RuntimeException('To be implemented'),
        };

        $chat = Chat::create([
            'id' => $chatData->id,
            'target_type' => $targetData->type->value,
            'target_id' => $targetData->id,
        ]);

        return new ChatData($chat->id, $chat->target);
    }

    public function createPrivate(PrivateData $privateData): PrivateData
    {
        $private = User::find($privateData->id);

        if ($private === null) {
            $private = User::create($privateData->toArray());
        }

        return PrivateData::from($private);
    }

    public function createGroup(GroupData $groupData): GroupData
    {
        $group = Group::create($groupData->toArray());

        return GroupData::from($group);
    }

    public function createSupergroup(SupergroupData $supergroupData): SupergroupData
    {
        $supergroup = Supergroup::create($supergroupData->toArray());

        return SupergroupData::from($supergroup);
    }

    public function createChannel(ChannelData $channelData): ChannelData
    {
        $channel = Channel::create($channelData->toArray());

        return ChannelData::from($channel);
    }

    public function appendUser(ChatData $chatData, UserData $userData): UserData
    {
        /** @var Chat $chat */
        $chat = Chat::find($chatData->id) ?? throw new ChatNotFoundException($chatData);

        if ($chat->users()->where('users.id', $userData->id)->doesntExist()) {
            $chat->chat_users()->save(new ChatUser(['user_id' => $userData->id]));
        }

        return $userData;
    }

    public function setInteractiveMode(ChatData $chatData, bool $interactiveMode): ChatData
    {
        /** @var Chat $chat */
        $chat = Chat::find($chatData->id) ?? throw new ChatNotFoundException($chatData);

        $chat->interactive_mode = $interactiveMode;
        $chat->save();

        return ChatData::from($chat);
    }
}
