<?php

declare(strict_types=1);

namespace App\Telegram\Actions\OpenAI\ChatGPT;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\Chat\ChatServiceInterface as OpenAIChatServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Actions\AbstractTelegramAction;

class AskKimiAction extends AbstractTelegramAction
{
    public function __construct(
        readonly OpenAIChatServiceInterface $chatService
    ) {}

    /**
     * @throws ChatMessageAlreadyExistsException
     * @throws TelegramUserNotFoundException
     * @throws ChatNotFoundException
     */
    public function handle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $message = $telegramDataRepository->getMessage();
        $chat = $this->chatRepository()->find($telegramDataRepository->getChat());

        if ($chat->interactive_mode === false) {
            return;
        }

        $answer = $this->chatService->interactiveAnswer($chat);

        $telegramService->replyToMessageAndSave($answer->content, $message);
    }

    public function chatRepository(): ChatRepositoryInterface
    {
        return app(ChatRepositoryInterface::class);
    }
}
