<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Commands;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\TelegramServiceInterface;

/**
 * @phpstan-require-extends AbstractTelegramCommand
 */
interface TelegramCommandInterface
{
    /**
     * @param TelegramServiceInterface $telegramService - service for interact with the telegram api
     * @param TelegramDataRepositoryInterface $telegramDataRepository - repository that contains telegram request information
     * @return void
     */
    public function onHandle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void;
}
