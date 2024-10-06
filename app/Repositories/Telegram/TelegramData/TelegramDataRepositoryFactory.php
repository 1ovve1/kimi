<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\TelegramData;

use App\Repositories\Abstract\RepositoryFactoryInterface;
use SergiX44\Nutgram\Nutgram;

class TelegramDataRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): TelegramDataRepositoryInterface
    {
        return app(NutgramTelegramDataRepository::class);
    }

    public function getFromNutgram(Nutgram $nutgram): TelegramDataRepositoryInterface
    {
        return new NutgramTelegramDataRepository($nutgram);
    }
}
