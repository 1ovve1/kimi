<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat\Characters;

use App\Data\OpenAI\Chat\DialogMessageData;

interface CharacterInterface
{
    /**
     * Collect dialog data into chat request body with other initial stuff that represent actual character
     */
    public function createRequestBody(DialogMessageData ...$dialogMessageData): array;
}
