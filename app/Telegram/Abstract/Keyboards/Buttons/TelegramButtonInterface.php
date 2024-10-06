<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Keyboards\Buttons;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\Callback\CallbackServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;

interface TelegramButtonInterface
{
    function handle(TelegramServiceInterface $telegramService, CallbackServiceInterface $callbackService, TelegramDataRepositoryInterface $telegramDataRepository): void;

    function make(): mixed;

    static function text(): string;

    static function name(): string;
}
