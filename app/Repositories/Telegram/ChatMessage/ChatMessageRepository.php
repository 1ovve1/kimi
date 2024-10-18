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
use App\Models\ChatMessage;
use App\Models\ChatUser;
use App\Repositories\Abstract\AbstractRepository;

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
            return $this->findInChat($chatMessageData, $chatData);
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
        $chatUser = ChatUser::findFromChatDataAndUserData($chatData, $userData);

        $chatMessage = $chatUser->chat_messages()->save(new ChatMessage($chatMessageData->toArray()));

        return ChatMessageData::from($chatMessage);
    }

    public function find(ChatMessageData $chatMessageData): ChatMessageData
    {
        $chatMessage = ChatMessage::findForChatMessageData($chatMessageData);

        return ChatMessageData::from($chatMessage);
    }

    public function findInChat(ChatMessageData $chatMessageData, ChatData $chatData): ChatMessageData
    {
        $chatMessage = ChatMessage::findForChatMessageDataAndChatData($chatMessageData, $chatData);

        return ChatMessageData::from($chatMessage);
    }

    /**
     * @throws ChatMessageNotFoundException
     */
    public function delete(ChatMessageData $chatMessageData): bool
    {
        return ChatMessage::findForChatMessageData($chatMessageData)->delete();
    }

    public function chat(ChatMessageData $chatMessageData): ChatData
    {
        $chatMessage = ChatMessage::findForChatMessageData($chatMessageData);

        return ChatData::from($chatMessage->chat_user->chat->load('target'));
    }
}
