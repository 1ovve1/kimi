<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\ChatMessage;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessage\ChatMessageNotFoundException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundInGivenChatException;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\ChatUser;
use App\Models\User;
use App\Repositories\Abstract\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class ChatMessageRepository extends AbstractRepository implements ChatMessageRepositoryInterface
{
    /**
     * @throws UserNotFoundInGivenChatException
     * @throws UserNotFoundException
     * @throws ChatNotFoundException
     */
    public function save(ChatData $chatData, UserData $userData, ChatMessageData $chatMessageData): ChatMessageData
    {
        try {
            return $this->find($chatMessageData);
        } catch (ChatMessageNotFoundException $e) {
            return $this->create($chatData, $userData, $chatMessageData);
        }
    }

    /**
     * @throws UserNotFoundException
     * @throws ChatNotFoundException
     * @throws UserNotFoundInGivenChatException
     */
    public function create(ChatData $chatData, UserData $userData, ChatMessageData $chatMessageData): ChatMessageData
    {
        /** @var Chat $chat */
        $chat = Chat::whereId($chatData->id)
            ->orWhereHas('target', fn(Builder $builder) => $builder->where('tg_id', $chatData->target->tg_id))
            ->first() ?? throw new ChatNotFoundException($chatData);
        /** @var User $user */
        $user = User::whereId($userData->id)
            ->orWhere('tg_id', $userData->tg_id)
            ->first() ?? throw new UserNotFoundException($userData);
        /** @var ChatUser $chatUser */
        $chatUser = $chat->chat_users()
            ->where('user_id', $user->id)
            ->first() ?? throw new UserNotFoundInGivenChatException($chatData, $userData);

        $chatMessage = $chatUser->chat_messages()->save(new ChatMessage($chatMessageData->toArray()));

        return ChatMessageData::from($chatMessage);
    }

    public function find(ChatMessageData $chatMessageData): ChatMessageData
    {
        $chatMessage = ChatMessage::find($chatMessageData->id) ?? throw new ChatMessageNotFoundException($chatMessageData);

        return ChatMessageData::from($chatMessage);
    }

    public function delete(ChatMessageData $chatMessageData): int
    {
        return ChatMessage::whereId($chatMessageData->id)->delete();
    }

    public function chat(ChatMessageData $chatMessageData): ChatData
    {
        /** @var ChatMessage $chatMessage */
        $chatMessage = ChatMessage::find($chatMessageData->id) ?? throw new ChatMessageNotFoundException($chatMessageData);

        return ChatData::from($chatMessage->chat_user->chat);
    }
}
