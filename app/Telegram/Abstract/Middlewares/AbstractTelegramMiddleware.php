<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Middlewares;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryFactory;
use App\Services\Telegram\TelegramServiceFactory;
use SergiX44\Nutgram\Nutgram;

abstract class AbstractTelegramMiddleware implements TelegramMiddlewareInterface
{
    public function __invoke(Nutgram $nutgram, $next): void
    {
        $this->handle(
            app(TelegramServiceFactory::class)->getFromNutgram($nutgram),
            app(TelegramDataRepositoryFactory::class)->getFromNutgram($nutgram),
            fn () => $next($nutgram)
        );
    }
}
