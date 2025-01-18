<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Commands;

use App\Exceptions\Telegram\Commands\ParameterNotFoundException;
use App\Telegram\Abstract\Traits\ReflectionAbleTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use ReflectionException;
use RuntimeException;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;

/**
 * Abstraction on Command class from Nutgram package with onHandle hook
 */
abstract class AbstractTelegramCommand extends Command implements TelegramCommandInterface
{
    use ReflectionAbleTrait;

    public function handle(Nutgram $nutgram): void
    {
        try {
            $this->callStaticMethodWithArgs('onHandle', ['nutgram' => $nutgram]);
        } catch (BindingResolutionException|ReflectionException $e) {
            throw new RuntimeException(previous: $e);
        }
    }

    protected function getParameter(string $name): string|int
    {
        return $this->getParameters()[$name] ?? throw new ParameterNotFoundException($name);
    }
}
