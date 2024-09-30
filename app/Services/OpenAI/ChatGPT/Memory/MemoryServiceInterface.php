<?php

declare(strict_types=1);

namespace App\Services\OpenAI\ChatGPT\Memory;

use App\Data\OpenAI\ChatGPT\GPTDialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use Illuminate\Support\Collection;

interface MemoryServiceInterface
{
    /**
     * @return Collection<GPTDialogMessageData>
     */
    public function collectMessagesForBoilerplate(ChatData $chatData): Collection;
}
