<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards;

use App\Services\OpenAI\Chat\ChatServiceInterface;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Telegram\Abstract\Keyboards\TelegramKeyboard;
use App\Telegram\Abstract\Keyboards\TelegramKeyboardInterface;
use App\Telegram\Keyboards\Buttons\BackToButton;
use App\Telegram\Keyboards\Buttons\Characters\DefaultCharacterButton;
use App\Telegram\Keyboards\Buttons\Characters\KimiCharacterButton;

class CharacterListKeyboardFactory
{
    public function get(): TelegramKeyboardInterface
    {
        return (new TelegramKeyboard)
            ->setDescription(__('telegram.keyboards.descriptions.select_character'))
            ->addColumn(
                new DefaultCharacterButton,
                new KimiCharacterButton,
                new BackToButton(fn () => (new StartKeyboardFactory())->withAiGreetingsDescription())
            );
    }
}
