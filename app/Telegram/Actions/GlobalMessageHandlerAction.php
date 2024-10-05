<?php

declare(strict_types=1);

namespace App\Telegram\Actions;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\ReplyWasNotFoundedException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Repositories\Telegram\User\UserRepositoryInterface;
use App\Services\OpenAI\Chat\ChatServiceInterface as OpenAICHatServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Actions\AbstractTelegramAction;

class GlobalMessageHandlerAction extends AbstractTelegramAction
{
    public function __construct(
        readonly OpenAICHatServiceInterface $chatService,
        readonly ChatRepositoryInterface $chatRepository,
        readonly UserRepositoryInterface $userRepository,
    ) {}

    /**
     * @throws ChatNotFoundException
     */
    public function handle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $chatMessageData = $telegramDataRepository->getMessage();
        $chatData = $this->chatRepository->find($telegramDataRepository->getChat());

        try {
            // find reply message - if not just skip it
            $userReply = $telegramDataRepository->getUserReply();

            // check if that was kimi
            if ($userReply->is_bot) {
                // if interactive mode enabled - answer in interactive mode, instead its just answer
                try {
                    $answer = match ($chatData->interactive_mode) {
                        true => $this->chatService->interactiveAnswer($chatData),
                        false => $this->chatService->answer($chatMessageData),
                    };
                } catch (ChatNotFoundException $e) {
                    $answer = $this->chatService->answer($chatMessageData);
                }

                $telegramService->replyToMessageAndSave($answer->content);
            }
        } catch (TelegramUserNotFoundException|ChatMessageAlreadyExistsException $e) {

        }
    }
}
