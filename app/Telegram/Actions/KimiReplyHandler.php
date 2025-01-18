<?php

declare(strict_types=1);

namespace App\Telegram\Actions;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessage\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\ReplyWasNotFoundedException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\OpenAI\Chat\ChatServiceInterface as OpenAICHatServiceInterface;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Actions\AbstractTelegramAction;

class KimiReplyHandler extends AbstractTelegramAction
{
    /**
     * @throws ChatMessageAlreadyExistsException
     * @throws ChatNotFoundException
     * @throws UserNotFoundException
     * @throws ReplyWasNotFoundedException
     * @throws TelegramUserNotFoundException
     */
    public function handle(
        OpenAICHatServiceInterface $chatService,
        TelegramServiceInterface $telegramService,
        TelegramDataServiceInterface $telegramDataService,
        TelegramDataRepositoryInterface $telegramDataRepository
    ): void {
        $chatData = $telegramDataService->resolveChat();

        try {
            if ($chatData->interactive_mode) {
                $userReply = $telegramDataService->resolveUserReply();

                if ($userReply->is_bot) {
                    $chatMessageData = $telegramDataService->resolveMessage();

                    $answer = $chatService->interactiveAnswer($chatData);

                    $telegramService->replyToMessageAndSave($answer->content, $chatMessageData);
                }
            } else {
                $userReply = $telegramDataRepository->getUserReply();

                if ($userReply->is_bot) {
                    $chatMessageData = $telegramDataRepository->getMessage();

                    $answer = $chatService->answer($chatData, $chatMessageData);

                    $telegramService->replyToMessage($answer->content, $chatMessageData);
                }
            }
        } catch (ReplyWasNotFoundedException) {
            // do nothing
        }
    }
}
