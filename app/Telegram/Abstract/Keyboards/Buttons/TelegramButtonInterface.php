<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Keyboards\Buttons;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\Callback\CallbackServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;

interface TelegramButtonInterface
{
    public function handle(TelegramServiceInterface $telegramService, CallbackServiceInterface $callbackService, TelegramDataRepositoryInterface $telegramDataRepository): void;

    public function make(): mixed;

    public static function text(): string;

    public static function name(): string;
}
