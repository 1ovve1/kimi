<?php

namespace App\Telegram\Middlewares;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Repositories\OpenAI\ChatGPT\Memory\MemoryRepositoryInterface;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\ChatMessage\ChatMessageRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Repositories\Telegram\User\UserRepositoryInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Middlewares\AbstractTelegramMiddleware;

class StoreTelegramRequestInDatabaseMiddleware extends AbstractTelegramMiddleware
{
    private readonly UserRepositoryInterface $userRepository;

    private readonly ChatRepositoryInterface $chatRepository;

    private readonly ChatMessageRepositoryInterface $chatMessageRepository;

    private readonly MemoryRepositoryInterface $memoryRepository;

    public function __construct()
    {
        $this->userRepository = app(UserRepositoryInterface::class);
        $this->chatRepository = app(ChatRepositoryInterface::class);
        $this->chatMessageRepository = app(ChatMessageRepositoryInterface::class);
        $this->memoryRepository = app(MemoryRepositoryInterface::class);
    }

    /**
     * @throws ChatNotFoundException
     */
    public function handle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $chat = $this->chatRepository->save(
            $telegramDataRepository->getChat()
        );

        // save bot user instance
        $bot = $this->userRepository->save(
            $telegramDataRepository->getMe()
        );
        $this->chatRepository->appendUser($chat, $bot);

        // save user instance
        try {
            $user = $this->userRepository->save(
                $telegramDataRepository->getUser()
            );
            $this->chatRepository->appendUser($chat, $user);

            if ($chat->interactive_mode) {
                $this->chatMessageRepository->save($chat, $user, $telegramDataRepository->getMessage());
                $this->memoryRepository->memorize($telegramDataRepository->getMessage());
            }
        } catch (TelegramUserNotFoundException|ChatMessageAlreadyExistsException) {
        }
    }
}
