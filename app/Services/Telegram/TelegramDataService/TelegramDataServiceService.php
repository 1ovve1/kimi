<?php

declare(strict_types=1);

namespace App\Services\Telegram\TelegramDataService;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\TelegramData\ReplyWasNotFoundedException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\ChatMessage\ChatMessageRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Repositories\Telegram\User\UserRepositoryInterface;
use App\Services\Abstract\AbstractService;

class TelegramDataServiceService extends AbstractService implements TelegramDataServiceServiceInterface
{
    public function __construct(
        readonly TelegramDataRepositoryInterface $telegramDataRepository,
        readonly ChatRepositoryInterface $chatRepository,
        readonly ChatMessageRepositoryInterface $chatMessageRepository,
        readonly UserRepositoryInterface $userRepository,
    )
    {
    }

    public function getMessage(): ChatMessageData
    {
        return $this->chatMessageRepository->find(
            $this->telegramDataRepository->getMessage()
        );
    }

    public function getReplyMessage(): ChatMessageData
    {
        // TODO: Implement getReplyMessage() method.
    }

    public function getChat(): ChatData
    {
        // TODO: Implement getChat() method.
    }

    public function getUser(): UserData
    {
        // TODO: Implement getUser() method.
    }

    public function getMe(): UserData
    {
        // TODO: Implement getMe() method.
    }

    public function getUserReply(): UserData
    {
        // TODO: Implement getUserReply() method.
    }


}
