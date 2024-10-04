<?php

namespace App\Http\Controllers\Actions\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\Nutgram;

class WebhookHandler extends Controller
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(Nutgram $nutgram)
    {
        $nutgram->run();
    }
}
