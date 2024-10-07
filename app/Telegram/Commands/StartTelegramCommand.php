<?php

namespace App\Telegram\Commands;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Repositories\Telegram\User\UserRepositoryInterface;
use App\Services\OpenAI\Chat\ChatServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Commands\AbstractTelegramCommand;
use App\Telegram\Keyboards\StartKeyboardFactory;

class StartTelegramCommand extends AbstractTelegramCommand
{
    protected string $command = 'start';

    protected ?string $description = 'start kimi';

    public function onHandle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        $chatRepository = $this->getChatRepository();
        $openAiChatService = $this->getOpenAIChatService();
        $userRepository = $this->getUserRepository();

        // initialize chat in db
        $chatData = $chatRepository->save($telegramDataRepository->getChat());
        try {
            $chatRepository->appendUser($chatData, $userRepository->save($telegramDataRepository->getUser()));
            $chatRepository->appendUser($chatData, $userRepository->save($telegramDataRepository->getMe()));
        } catch (ChatNotFoundException|TelegramUserNotFoundException $e) {
        }

        $startKeyboardFactory = new StartKeyboardFactory;
        $greetings = $openAiChatService->dryAnswer(__('openai.chat.characters.kimi.greetings'))->content;

        $telegramService->sendMessageWithKeyboard($greetings, $startKeyboardFactory->get());
    }

    public function getOpenAIChatService(): ChatServiceInterface
    {
        return app(ChatServiceInterface::class);
    }

    public function getChatRepository(): ChatRepositoryInterface
    {
        return app(ChatRepositoryInterface::class);
    }

    public function getUserRepository(): UserRepositoryInterface
    {
        return app(UserRepositoryInterface::class);
    }
}
