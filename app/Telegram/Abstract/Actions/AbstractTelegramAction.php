<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Actions;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryFactory;
use App\Services\Telegram\TelegramServiceFactory;
use SergiX44\Nutgram\Nutgram;

abstract class AbstractTelegramAction implements TelegramActionInterface
{
    private array $params = [];

    public function __invoke(Nutgram $nutgram, ...$params): void
    {
        $this->setParameters($params);

        $this->handle(
            app(TelegramServiceFactory::class)->getFromNutgram($nutgram),
            app(TelegramDataRepositoryFactory::class)->fromNutgram($nutgram)
        );
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
