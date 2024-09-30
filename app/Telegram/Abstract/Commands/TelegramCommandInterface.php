<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Commands;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\TelegramServiceInterface;

interface TelegramCommandInterface
{
    /**
     * Main hook we would work to
     */
    public function onHandle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void;
}
