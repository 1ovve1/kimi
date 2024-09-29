<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Middlewares;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\TelegramServiceInterface;

interface TelegramMiddlewareInterface
{
    public function handle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void;
}
