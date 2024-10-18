<?php

declare(strict_types=1);

namespace App\Services\Telegram\TelegramData;

use App\Services\Abstract\ServiceFactoryInterface;
use Illuminate\Support\Facades\App;

class TelegramDataServiceFactory implements ServiceFactoryInterface
{
    public function get(array $params = []): TelegramDataServiceInterface
    {
        return App::make(NutgramTelegramDataService::class, $params);
    }
}
