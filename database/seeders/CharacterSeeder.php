<?php

namespace Database\Seeders;

use App\Enums\Models\CharacterEnum;
use App\Models\Character;
use Illuminate\Database\Seeder;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Character::create([
            'name' => CharacterEnum::DEFAULT,
            'prompt_ru' => CharacterEnum::DEFAULT->resolvePrompt('ru'),
            'prompt_en' => CharacterEnum::DEFAULT->resolvePrompt( 'en'),
        ]);

        Character::create([
            'name' => CharacterEnum::KIMI,
            'prompt_ru' => CharacterEnum::KIMI->resolvePrompt('ru'),
            'prompt_en' => CharacterEnum::KIMI->resolvePrompt('en'),
        ]);
    }
}
