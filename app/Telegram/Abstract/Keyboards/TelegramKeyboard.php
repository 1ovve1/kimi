<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Keyboards;

use App\Telegram\Abstract\Keyboards\Buttons\TelegramButtonInterface;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class TelegramKeyboard implements TelegramKeyboardInterface
{
    protected InlineKeyboardMarkup $inlineKeyboardMarkup;

    public function __construct()
    {
        $this->inlineKeyboardMarkup = InlineKeyboardMarkup::make();
    }

    public function make(): InlineKeyboardMarkup
    {
        return $this->inlineKeyboardMarkup;
    }

    public function addRow(TelegramButtonInterface ...$button): TelegramKeyboardInterface
    {
        $collect = collect($button)->map(fn (TelegramButtonInterface $x) => $x->make());

        $this->inlineKeyboardMarkup->addRow(...$collect->toArray());

        return $this;
    }
}
