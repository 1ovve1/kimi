<?php

namespace App\Data\OpenAI\Chat;

use App\Enums\Models\CharacterEnum;
use Spatie\LaravelData\Data;

class CharacterData extends Data
{
    public function __construct(
        readonly int $id,
        readonly CharacterEnum $name,
        readonly string $prompt
    ) {}

    static public function makeDefault(): self
    {
        return new self(1, CharacterEnum::default(), CharacterEnum::default()->resolvePrompt());
    }
}
