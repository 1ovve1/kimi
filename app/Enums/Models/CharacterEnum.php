<?php

declare(strict_types=1);

namespace App\Enums\Models;

enum CharacterEnum: string
{
    case DEFAULT = 'default';
    case KIMI = 'Kimi No Sei';
    case CHIKA = 'Chika Fujiwara';

    public static function default(): self
    {
        return self::DEFAULT;
    }

    public function resolvePrompt(?string $locale = null): string
    {
        return match ($this) {
            self::DEFAULT => __('openai.chat.characters.default', locale: $locale),
            self::KIMI => __('openai.chat.characters.kimi', locale: $locale),
            self::CHIKA => __('openai.chat.characters.chika', locale: $locale),
        };
    }
}
