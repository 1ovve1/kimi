<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\ChatMessage;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\UserNotFoundException;
use App\Exceptions\Repositories\Telegram\UserNotFoundInGivenChatException;
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
     * @throws ChatMessageAlreadyExistsException
     * @throws ChatNotFoundException
     */
    public function save(ChatData $chatData, UserData $userData, ChatMessageData $chatMessageData): ChatMessageData
    {
        /** @var ChatMessage $chatMessage */
        $chatMessage = ChatMessage::find($chatMessageData->id);

        if ($chatMessage) {
            throw new ChatMessageAlreadyExistsException($chatMessageData);
        }

        return $this->create($chatData, $userData, $chatMessageData);
    }

    /**
     * @throws UserNotFoundException
     * @throws ChatNotFoundException
     * @throws UserNotFoundInGivenChatException
     */
    public function create(ChatData $chatData, UserData $userData, ChatMessageData $chatMessageData): ChatMessageData
    {
        /** @var Chat $chat */
        $chat = Chat::find($chatData->id) ?? throw new ChatNotFoundException($chatData);
        /** @var User $user */
        $user = User::find($userData->id) ?? throw new UserNotFoundException($userData);
        /** @var ChatUser $chatUser */
        $chatUser = $chat->chat_users()->where('user_id', $user->id)->first() ?? throw new UserNotFoundInGivenChatException($chatData, $userData);

        $chatMessage = $chatUser->chat_messages()->save(new ChatMessage($chatMessageData->toArray()));

        return ChatMessageData::from($chatMessage);
    }

    public function delete(ChatData $chatData, ChatMessageData $chatMessageData): int
    {
        /** @var Chat $chat */
        $chat = Chat::find($chatData->id) ?? throw new ChatNotFoundException($chatData);

        return ChatMessage::whereHas("chat_user", fn (Builder $builder) =>
            $builder->where('chat_id', $chat->id)
        )->delete();
    }
}
