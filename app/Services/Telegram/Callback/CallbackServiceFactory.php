<?php

declare(strict_types=1);

namespace App\Services\Telegram\Callback;

use App\Services\Abstract\ServiceFactoryInterface;
use Illuminate\Support\Facades\App;
use SergiX44\Nutgram\Nutgram;


class CallbackServiceFactory implements ServiceFactoryInterface
{
    function get(): CallbackServiceInterface
    {
        return App::make(NutgramCallbackService::class);
    }

    function getFromNutgram(Nutgram $nutgram): CallbackServiceInterface
    {
        return App::make(NutgramCallbackService::class, ['nutgram' => $nutgram]);
    }
}
