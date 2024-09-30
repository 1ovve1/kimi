<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Services\Abstract\ServiceFactoryInterface;
use SergiX44\Nutgram\Nutgram;

class TelegramServiceFactory implements ServiceFactoryInterface
{
    public function get(): TelegramServiceInterface
    {
        return app(NutgramTelegramService::class);
    }

    public function getFromNutgram(Nutgram $nutgram): TelegramServiceInterface
    {
        return new NutgramTelegramService(
            $nutgram
        );
    }
}
