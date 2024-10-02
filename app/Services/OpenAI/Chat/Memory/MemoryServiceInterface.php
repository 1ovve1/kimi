<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat\Memory;

use App\Data\OpenAI\Chat\DialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use Illuminate\Support\Collection;

interface MemoryServiceInterface
{
    /**
     * @return Collection<DialogMessageData>
     */
    public function collectMemories(ChatData $chatData): Collection;

    public function memorize(ChatMessageData $chatData): ChatMessageData;

    public function reset(ChatData $chatData): int;
}
