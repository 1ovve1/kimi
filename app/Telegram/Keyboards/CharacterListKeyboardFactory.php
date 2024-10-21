<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards;

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
            ->addColumn(
                new DefaultCharacterButton,
                new KimiCharacterButton,
                new BackToButton((new StartKeyboardFactory)->get()),
            );
    }
}
