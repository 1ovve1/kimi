<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Buttons;

use App\Repositories\OpenAI\Chat\Character\CharacterRepositoryInterface;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Keyboards\Buttons\AbstractTelegramButton;
use App\Telegram\Keyboards\CharacterListKeyboardFactory;

class SelectCharacterButton extends AbstractTelegramButton
{
    public function handle(
        TelegramServiceInterface $telegramService,
        CharacterListKeyboardFactory $characterListKeyboardFactory
    ): void {
        $telegramService->updateKeyboard($characterListKeyboardFactory->get());
    }

    public function text(
        TelegramDataServiceInterface $telegramDataService,
        CharacterRepositoryInterface $characterRepository,
    ): string {
        $chat = $telegramDataService->resolveChat();
        $character = $characterRepository->findForChat($chat);

        return __('telegram.keyboards.buttons.select_character.name', ['name' => $character->name->value]);
    }
}
