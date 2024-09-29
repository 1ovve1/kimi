<?php

namespace App\Telegram\Commands\ChatGPT;

use App\Exceptions\Repositories\Telegram\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\Tokenizer\TokenizerServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Commands\AbstractTelegramCommand;

class AskTelegramCommand extends AbstractTelegramCommand
{
    protected string $command = 'ask {text}';

    protected ?string $description = 'ask Kimi about something';

    /**
     * @throws ChatMessageAlreadyExistsException
     * @throws TelegramUserNotFoundException
     */
    public function onHandle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $telegramService->replyToMessageAndSave('This is a command! '.$this->getTokenizer()->count($telegramDataRepository->getMessage()->text));
    }

    private function getTokenizer(): TokenizerServiceInterface
    {
        return app(TokenizerServiceInterface::class);
    }
}
