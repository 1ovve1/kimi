<?php

declare(strict_types=1);

namespace App\Enums\Models;

enum CharacterEnum: string
{
    case DEFAULT = 'default';
    case KIMI = 'Kimi No Sei';

    public static function default(): self
    {
        return self::KIMI;
    }

    public function resolvePrompt(?string $locale = null): string
    {
        return match ($this) {
            self::DEFAULT => __('openai.chat.characters.default', locale: $locale),
            self::KIMI => __('openai.chat.characters.kimi', locale: $locale),
        };
    }
}
