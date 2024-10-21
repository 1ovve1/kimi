<?php

declare(strict_types=1);

namespace App\Repositories\OpenAI\Chat\Character;

use App\Data\OpenAI\Chat\CharacterData;
use App\Data\Telegram\Chat\ChatData;
use App\Enums\Models\CharacterEnum;
use App\Exceptions\Repository\OpenAI\Character\CharacterNotFoundException;
use App\Models\Character;
use App\Models\Chat;
use App\Repositories\Abstract\AbstractRepository;

class CharacterRepository extends AbstractRepository implements CharacterRepositoryInterface
{
    public function findForChat(ChatData $chat): CharacterData
    {
        $chat = Chat::findForChatData($chat);

        $character = $chat->character ?? Character::whereName(CharacterEnum::default())->first() ?? throw new CharacterNotFoundException(CharacterEnum::default());

        return CharacterData::from($character);
    }

    public function findByEnum(CharacterEnum $characterEnum): CharacterData
    {
        $character = Character::findByEnum($characterEnum);

        return CharacterData::from($character);
    }
}
