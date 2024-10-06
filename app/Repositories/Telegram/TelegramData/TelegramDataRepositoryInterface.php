<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\TelegramData;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\TelegramData\ReplyWasNotFoundedException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Repositories\Abstract\RepositoryInterface;

interface TelegramDataRepositoryInterface extends RepositoryInterface
{
    /**
     * get current message request from telegram api response
     */
    public function getMessage(): ChatMessageData;

    /**
     * Get reply message
     *
     * @throws ReplyWasNotFoundedException
     */
    public function getReplyMessage(): ChatMessageData;

    /**
     * Get current chat from telegram api response
     */
    public function getChat(): ChatData;

    /**
     * Get current user from telegram api response
     *
     * @throws TelegramUserNotFoundException
     */
    public function getUser(): UserData;

    /**
     * Get bot instance
     */
    public function getMe(): UserData;

    /**
     * Get user in reply message
     *
     * @throws ReplyWasNotFoundedException
     */
    public function getUserReply(): UserData;
}
