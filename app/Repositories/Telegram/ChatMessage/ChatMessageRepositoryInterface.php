<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\ChatMessage;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessage\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\ChatMessage\ChatMessageNotFoundException;
use App\Repositories\Abstract\RepositoryInterface;

interface ChatMessageRepositoryInterface extends RepositoryInterface
{
    /**
     * Same as create() method but throw exception if message already exists in db
     *
     * @throws ChatMessageAlreadyExistsException
     */
    public function save(ChatData $chatData, UserData $userData, ChatMessageData $chatMessageData, ?ChatMessageData $replyData = null): ChatMessageData;

    /**
     * Store new chat message data in db
     *
     * @param  ChatData  $chatData  - current chat
     * @param  UserData  $userData  - user that send message
     * @param  ChatMessageData  $chatMessageData  - message id and content
     */
    public function create(ChatData $chatData, UserData $userData, ChatMessageData $chatMessageData, ?ChatMessageData $replyData = null): ChatMessageData;

    /**
     * Find chat message by id
     *
     * @throws ChatMessageNotFoundException
     */
    public function find(ChatMessageData $chatMessageData): ChatMessageData;

    /**
     * Find chat message by tg_id in given chat
     *
     * @throws ChatMessageNotFoundException
     * @throws ChatNotFoundException
     */
    public function findInChat(ChatMessageData $chatMessageData, ChatData $chatData): ChatMessageData;

    /**
     * Delete chat message from db
     *
     * @throws ChatMessageNotFoundException
     */
    public function delete(ChatMessageData $chatMessageData): bool;

    // RELATIONS

    /**
     * Find chat for given ChatMessageData instance (existed in db)
     *
     * @throws ChatMessageNotFoundException
     */
    public function chat(ChatMessageData $chatMessageData): ChatData;
}
