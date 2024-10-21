<?php

declare(strict_types=1);

namespace App\Repositories\OpenAI\Chat\Character;

use App\Data\OpenAI\Chat\CharacterData;
use App\Data\Telegram\Chat\ChatData;
use App\Enums\Models\CharacterEnum;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repository\OpenAI\Character\CharacterNotFoundException;
use App\Repositories\Abstract\RepositoryInterface;

interface CharacterRepositoryInterface extends RepositoryInterface
{
    /**
     * @throws ChatNotFoundException
     * @throws CharacterNotFoundException
     */
    public function findForChat(ChatData $chat): CharacterData;

    /**
     * @throws CharacterNotFoundException
     */
    public function findByEnum(CharacterEnum $characterEnum): CharacterData;
}
