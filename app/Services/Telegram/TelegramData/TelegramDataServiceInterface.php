<?php

declare(strict_types=1);

namespace App\Services\Telegram\TelegramData;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessage\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\ReplyWasNotFoundedException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;

/**
 * Service contains actions that require pre-saved entities
 *
 * All actions simply repeated by TelegramDataRepositoryInterface, but they are storing entities in db
 * with all required dependencies. All actions are performed lazy:
 * 1. We take the entity from request;
 * 2. Searching entity in DB:
 *  2.1. If entity doesn't exist - store it in db and return;
 *  2.2. If entity exists - return it with all db flags.
 *
 * Its also contains methods for the force data saving, like @link self::storeAllTelegramDataInDb()
 */
interface TelegramDataServiceInterface
{
    /**
     * Store all existed telegram request data in database
     */
    public function storeAllTelegramDataInDb(): void;

    /**
     * Store only chat and users information in db
     * (helpfully for /start command)
     */
    public function storeChatAndUsersInDb(): void;

    /**
     * Lazy get save current chat data
     */
    public function resolveChat(): ChatData;

    /**
     * Get c
     *
     * @throws ChatMessageAlreadyExistsException
     * @throws ChatNotFoundException
     * @throws UserNotFoundException
     */
    public function resolveMessage(): ChatMessageData;

    /**
     * @throws UserNotFoundException
     * @throws ReplyWasNotFoundedException
     * @throws ChatMessageAlreadyExistsException
     * @throws ChatNotFoundException
     */
    public function resolveReplyMessage(): ChatMessageData;

    /**
     * @throws UserNotFoundException
     * @throws ChatNotFoundException
     */
    public function resolveUser(): UserData;

    /**
     * @throws UserNotFoundException
     * @throws ChatNotFoundException
     */
    public function resolveMe(): UserData;

    /**
     * @throws UserNotFoundException
     * @throws ChatNotFoundException
     * @throws ReplyWasNotFoundedException
     */
    public function resolveUserReply(): UserData;
}
