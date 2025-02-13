<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares;

use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Telegram\Abstract\Middlewares\AbstractTelegramMiddleware;

class AdminMiddleware extends AbstractTelegramMiddleware
{
    public function handle(
        TelegramDataServiceInterface $telegramDataService,
        callable $next
    ): void {
        $user = $telegramDataService->resolveUser();

        if ($user->tg_id !== config('telegram.tg_id')) {
            return;
        }

        $next();
    }
}
