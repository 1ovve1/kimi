<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Keyboards\Buttons;

/**
 * @phpstan-require-extends AbstractTelegramButton
 *
 * @method void handle()
 * @method string text()
 */
interface TelegramButtonInterface
{
    public function make(): mixed;

    public static function name(): string;
}
