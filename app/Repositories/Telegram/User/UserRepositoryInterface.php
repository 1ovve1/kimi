<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\User;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;
use App\Repositories\Abstract\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function save(UserData $userData): UserData;

    public function findByMessage(ChatMessageData $chatMessageData): UserData;

    /**
     * @throws ChatNotFoundException
     * @throws UserNotFoundException
     */
    public function isAdmin(ChatData $chatData, UserData $userData): bool;
}
