<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Middlewares;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\TelegramServiceInterface;

interface TelegramMiddlewareInterface
{
    /**
     * @param  TelegramServiceInterface  $telegramService  - service for interact with the telegram api
     * @param  TelegramDataRepositoryInterface  $telegramDataRepository  - repository that contains telegram request information
     * @param  callable  $next  - action callback
     */
    public function handle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository, callable $next): void;
}
