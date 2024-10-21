<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Buttons\Characters;

use App\Enums\Models\CharacterEnum;

class KimiCharacterButton extends AbstractCharacterButton
{
    public function __construct()
    {
        parent::__construct(CharacterEnum::KIMI);
    }

}
