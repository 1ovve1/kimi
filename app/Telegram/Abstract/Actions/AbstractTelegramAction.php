<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Actions;

use App\Telegram\Abstract\Traits\ReflectionAbleTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use ReflectionException;
use RuntimeException;
use SergiX44\Nutgram\Nutgram;

abstract class AbstractTelegramAction implements TelegramActionInterface
{
    use ReflectionAbleTrait;

    private array $params = [];

    public function __invoke(Nutgram $nutgram, ...$params): void
    {
        $this->setParameters($params);

        try {
            $this->callStaticMethodWithArgs('handle', ['nutgram' => $nutgram]);
        } catch (BindingResolutionException|ReflectionException $e) {
            throw new RuntimeException(previous: $e);
        }
    }

    private function setParameters(array $params): void
    {
        $this->params = $params;
    }

    protected function getParameters(): array
    {
        return $this->params;
    }

    protected function getParameter(int $position): mixed
    {
        return $this->params[$position];
    }
}
