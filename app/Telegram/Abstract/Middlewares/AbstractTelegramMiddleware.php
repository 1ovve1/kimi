<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Middlewares;

use App\Telegram\Abstract\Traits\ReflectionAbleTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use ReflectionException;
use RuntimeException;
use SergiX44\Nutgram\Nutgram;

abstract class AbstractTelegramMiddleware implements TelegramMiddlewareInterface
{
    use ReflectionAbleTrait;

    public function __invoke(Nutgram $nutgram, callable $next): void
    {
        try {
            $this->callStaticMethodWithArgs('handle', ['nutgram' => $nutgram, 'next' => fn () => $next($nutgram)]);
        } catch (BindingResolutionException|ReflectionException $e) {
            throw new RuntimeException(previous: $e);
        }
    }
}
