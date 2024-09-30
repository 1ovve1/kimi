<?php

declare(strict_types=1);

namespace App\Telegram\Actions;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Actions\AbstractTelegramAction;

class GlobalMessageHandlerAction extends AbstractTelegramAction
{
    public function handle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void
    {
        // TODO: Implement handle() method.
    }
}
