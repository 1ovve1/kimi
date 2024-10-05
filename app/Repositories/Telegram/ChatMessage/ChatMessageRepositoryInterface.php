<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\ChatMessage;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\ChatMessageAlreadyExistsException;
use App\Repositories\Abstract\RepositoryInterface;

interface ChatMessageRepositoryInterface extends RepositoryInterface
{
    /**
     * Same as create() method but throw exception if message already exists in db
     *
     * @throws ChatMessageAlreadyExistsException
     */
    public function save(ChatData $chatData, UserData $userData, ChatMessageData $chatMessageData): ChatMessageData;

    /**
     * Store new chat message data in db
     *
     * @param  ChatData  $chatData  - current chat
     * @param  UserData  $userData  - user that send message
     * @param  ChatMessageData  $chatMessageData  - message id and content
     */
    public function create(ChatData $chatData, UserData $userData, ChatMessageData $chatMessageData): ChatMessageData;

    /**
     * Delete chat message from db
     */
    public function delete(ChatData $chatData, ChatMessageData $chatMessageData): int;
}
