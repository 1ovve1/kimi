<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Keyboards\Buttons;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryFactory;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\Callback\CallbackServiceFactory;
use App\Services\Telegram\TelegramServiceFactory;
use Illuminate\Support\Str;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

abstract class AbstractTelegramButton implements TelegramButtonInterface
{
    function make(): InlineKeyboardButton
    {
        return InlineKeyboardButton::make(
            text: $this->text(),
            callback_data: $this->name(),
        );
    }

    static function name(): string
    {
        return Str::replace('\\', '.', self::class);
    }

    public function __invoke(Nutgram $nutgram): void
    {
        $this->handle(
            app(TelegramServiceFactory::class)->getFromNutgram($nutgram),
            app(CallbackServiceFactory::class)->getFromNutgram($nutgram),
            app(TelegramDataRepositoryFactory::class)->getFromNutgram($nutgram),
        );
    }
}
