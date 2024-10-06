<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Middlewares\AbstractTelegramMiddleware;

class AutoDeleteMessagesMiddleware extends AbstractTelegramMiddleware
{
    public function handle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository, callable $next): void
    {
        $next();

        $telegramService->deleteMessage($telegramDataRepository->getMessage());
    }
}
