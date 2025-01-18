<?php

namespace App\Telegram\Middlewares;

use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Telegram\Abstract\Middlewares\AbstractTelegramMiddleware;

class StoreTelegramRequestInDatabaseMiddleware extends AbstractTelegramMiddleware
{
    /**
     * @throws ChatNotFoundException
     */
    public function handle(
        TelegramDataServiceInterface $telegramDataService, callable $next
    ): void {
        $telegramDataService->storeAllTelegramDataInDb();

        $next();
    }
}
