<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Services\Abstract\ServiceFactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\Nutgram;

class TelegramServiceFactory implements ServiceFactoryInterface
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function get(array $params = []): TelegramServiceInterface
    {
        return app(NutgramTelegramService::class, [
            'nutgram' => new Nutgram(config('nutgram.token')),
            ...$params,
        ]);
    }

    public function getFromNutgram(Nutgram $nutgram): TelegramServiceInterface
    {
        return app(NutgramTelegramService::class, [
            'nutgram' => $nutgram,
        ]);
    }
}
