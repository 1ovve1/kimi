<?php

declare(strict_types=1);

namespace App\Services\Telegram\Callback;

use App\Services\Abstract\ServiceFactoryInterface;
use Illuminate\Support\Facades\App;
use SergiX44\Nutgram\Nutgram;

class CallbackServiceFactory implements ServiceFactoryInterface
{
    public function get(array $params = []): CallbackServiceInterface
    {
        return App::make(NutgramCallbackService::class, $params);
    }

    public function getFromNutgram(Nutgram $nutgram): CallbackServiceInterface
    {
        return App::make(NutgramCallbackService::class, ['nutgram' => $nutgram]);
    }
}
