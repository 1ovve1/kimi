<?php

declare(strict_types=1);

namespace App\Telegram\Actions;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\ReplyWasNotFoundedException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\ChatGPT\ChatGPTServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Actions\AbstractTelegramAction;

class GlobalMessageHandlerAction extends AbstractTelegramAction
{
    public function __construct(
        readonly ChatGPTServiceInterface $chatGPTService,
        readonly ChatRepositoryInterface $chatRepository
    ) {}

    /**
     * @throws ChatNotFoundException
     */
    public function handle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $chatMessageData = $telegramDataRepository->getMessage();
        $chatData = $this->chatRepository->find($telegramDataRepository->getChat());

        try {
            // check if bot was replied
            $telegramDataRepository->getReplyMessage();

            // if interactive mode enabled - answer with memory, instead its just answer
            $answer = match ($chatData->interactive_mode) {
                true => $this->chatGPTService->answerWithMemory($chatData),
                false => $this->chatGPTService->answer($chatMessageData),
            };

            $telegramService->replyToMessageAndSave($answer->content);
        } catch (TelegramUserNotFoundException|ReplyWasNotFoundedException|ChatMessageAlreadyExistsException $e) {

        }
    }
}
