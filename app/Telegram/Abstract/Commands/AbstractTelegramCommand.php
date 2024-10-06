<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Commands;

use App\Exceptions\Telegram\Commands\ParameterNotFoundException;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryFactory;
use App\Services\Telegram\TelegramServiceFactory;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;

/**
 * Abstraction on Command class from Nutgram package with onHandle hook
 */
abstract class AbstractTelegramCommand extends Command implements TelegramCommandInterface
{
    public function handle(Nutgram $nutgram): void
    {
        $this->onHandle(
            app(TelegramServiceFactory::class)->getFromNutgram($nutgram),
            app(TelegramDataRepositoryFactory::class)->getFromNutgram($nutgram),
        );
    }

    protected function getParameter(string $name): string|int
    {
        return $this->getParameters()[$name] ?? throw new ParameterNotFoundException($name);
    }
}
