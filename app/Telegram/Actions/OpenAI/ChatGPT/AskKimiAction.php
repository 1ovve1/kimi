<?php

declare(strict_types=1);

namespace App\Telegram\Actions\OpenAI\ChatGPT;

use App\Exceptions\Repositories\Telegram\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\ChatGPT\ChatGPTServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Actions\AbstractTelegramAction;

class AskKimiAction extends AbstractTelegramAction
{
    public readonly ChatGPTServiceInterface $chatGPTService;

    public function __construct()
    {
        $this->chatGPTService = app(ChatGPTServiceInterface::class);
    }

    /**
     * @throws ChatMessageAlreadyExistsException
     * @throws TelegramUserNotFoundException
     */
    public function handle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $message = $telegramDataRepository->getMessage();
        $chat = $telegramDataRepository->getChat();

        if ($chat->interactive_mode === false) {
            return;
        }

        $answer = $this->chatGPTService->answerWithMemory($chat);

        $telegramService->replyToMessageAndSave($answer->content, $message);
    }
}
