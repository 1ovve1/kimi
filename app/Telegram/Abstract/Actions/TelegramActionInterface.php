<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Actions;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\TelegramServiceInterface;

interface TelegramActionInterface
{
    /**
     * @param TelegramServiceInterface $telegramService - service for interacting with telegram api
     * @param TelegramDataRepositoryInterface $telegramDataRepository - repository that contains telegram request information
     */
    public function handle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void;
}
