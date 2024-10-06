<?php

declare(strict_types=1);

namespace App\Services\Telegram\TelegramDataService;

use App\Services\Abstract\ServiceFactoryInterface;
use Illuminate\Support\Facades\App;


class TelegramDataServiceServiceFactory implements ServiceFactoryInterface
{
    function get(): TelegramDataServiceServiceInterface
    {
        return App::make(TelegramDataServiceService::class);
    }
}
