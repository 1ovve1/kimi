<?php

namespace App\Models;

use App\Data\OpenAI\Chat\CharacterData;
use App\Enums\Models\CharacterEnum;
use App\Exceptions\Repository\OpenAI\Character\CharacterNotFoundException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'prompt_ru',
        'prompt_en',
    ];

    protected $casts = [
        'name' => CharacterEnum::class,
    ];

    protected $appends = ['prompt'];
    protected $hidden = ['prompt_ru', 'prompt_en'];

    public function prompt(): Attribute
    {
        return new Attribute(
            get: fn() => match (config('app.locale')) {
                'en' => $this->prompt_en,
                'ru' => $this->prompt_ru,
                default => '',
            }
        );
    }

    /**
     * @return HasMany<Chat>
     */
    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }

    static function findForCharacterData(CharacterData $characterData): self
    {
        return Character::whereName($characterData->name)->first() ?? throw new CharacterNotFoundException($characterData->name);
    }

    public static function findByEnum(CharacterEnum $characterEnum)
    {
        return Character::whereName($characterEnum)->first() ?? throw new CharacterNotFoundException($characterEnum);
    }
}
