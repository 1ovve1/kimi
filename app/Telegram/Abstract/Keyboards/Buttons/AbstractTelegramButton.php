<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Keyboards\Buttons;

use App\Telegram\Abstract\Traits\ReflectionAbleTrait;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use ReflectionException;
use RuntimeException;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

abstract class AbstractTelegramButton implements TelegramButtonInterface
{
    use ReflectionAbleTrait;

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function make(): InlineKeyboardButton
    {
        return InlineKeyboardButton::make(
            /** @link TelegramButtonInterface::text() */
            text: $this->callStaticMethodWithArgs('text'),
            callback_data: self::name(),
        );
    }

    public static function name(): string
    {
        return Str::replace('\\', '.', static::class);
    }

    public function __invoke(Nutgram $nutgram): void
    {
        try {
            $this->callStaticMethodWithArgs('handle', ['nutgram' => $nutgram]);
        } catch (BindingResolutionException|ReflectionException $e) {
            throw new RuntimeException(previous: $e);
        }
    }
}
