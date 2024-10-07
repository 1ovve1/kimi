<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\Chat;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\Types\ChannelData;
use App\Data\Telegram\Chat\Types\GroupData;
use App\Data\Telegram\Chat\Types\PrivateData;
use App\Data\Telegram\Chat\Types\SupergroupData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\Chat\ChannelNotFoundException;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\Chat\GroupNotFoundException;
use App\Exceptions\Repositories\Telegram\Chat\SupergroupNotFoundException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;
use App\Models\Channel;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Group;
use App\Models\Supergroup;
use App\Models\User;
use App\Repositories\Abstract\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;
use SergiX44\Nutgram\Telegram\Properties\ChatType;

class ChatRepository extends AbstractRepository implements ChatRepositoryInterface
{
    public function save(ChatData $chatData): ChatData
    {
        try {
            return $this->find($chatData);
        } catch (ChatNotFoundException $e) {
            return $this->create($chatData);
        }
    }

    public function create(ChatData $chatData): ChatData
    {
        $targetData = match ($chatData->target->type) {
            ChatType::PRIVATE => $this->createPrivate($chatData->target),
            ChatType::GROUP => $this->findOrCreateGroup($chatData->target),
            ChatType::SUPERGROUP => $this->findOrCreateSupergroup($chatData->target),
            ChatType::CHANNEL => $this->findOrCreateChannel($chatData->target),
            ChatType::SENDER => throw new RuntimeException('To be implemented'),
        };

        $chat = Chat::create([
            'target_type' => $targetData->type->value,
            'target_id' => $targetData->id,
        ]);

        return new ChatData($chat->id, $chat->target);
    }

    public function createPrivate(PrivateData $privateData): PrivateData
    {
        try {
            $private = User::findForUserData($privateData);
        } catch (UserNotFoundException $e) {
            $private = User::create($privateData->toArray());
        }

        return PrivateData::from($private);
    }

    public function findOrCreateGroup(GroupData $groupData): GroupData
    {
        try {
            $group = Group::findForGroupData($groupData);
        } catch (GroupNotFoundException $e) {
            $group = Group::create($groupData->toArray());
        }

        return GroupData::from($group);
    }

    public function findOrCreateSupergroup(SupergroupData $supergroupData): SupergroupData
    {
        try {
            $supergroup = Supergroup::findForSupergroupData($supergroupData);
        } catch (SupergroupNotFoundException $e) {
            $supergroup = Supergroup::create($supergroupData->toArray());
        }

        return SupergroupData::from($supergroup);
    }

    public function findOrCreateChannel(ChannelData $channelData): ChannelData
    {
        try {
            $channel = Channel::findForChannelData($channelData);
        } catch (ChannelNotFoundException $e) {
            $channel = Channel::create($channelData);
        }

        return ChannelData::from($channel);
    }

    public function appendUser(ChatData $chatData, UserData $userData): UserData
    {
        $chat = Chat::findForChatData($chatData);
        $user = User::findForUserData($userData);

        if ($chat->chat_users()->where('chat_users.id', $user->id)->doesntExist()) {
            $chat->chat_users()->save(new ChatUser(['user_id' => $user->id]));
        }

        return $userData;
    }

    public function setInteractiveMode(ChatData $chatData, bool $interactiveMode): ChatData
    {
        $chat = Chat::findForChatData($chatData);

        $chat->interactive_mode = $interactiveMode;
        $chat->save();

        return ChatData::from($chat);
    }

    public function find(ChatData $chatData): ChatData
    {
        $chat = Chat::findForChatData($chatData);

        return ChatData::from($chat);
    }
}
