<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat\Memory;

use App\Data\OpenAI\Chat\DialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use Illuminate\Support\Collection;

class CachedMemoryService extends MemoryService implements MemoryServiceInterface
{
    public function collectMemories(ChatData $chatData): Collection
    {
        $collection = new Collection;
        $messages = $this->memoryRepository->getAllLatest($chatData);

        $tokensBuffer = 0;

        /** @var DialogMessageData $message */
        foreach ($messages->reverse() as $message) {
            $collection->unshift($message);

            $tokensBuffer += $message->tokens_count ?? $this->tokenizerService->count($message->content);

            if ($tokensBuffer > $this->tokenizerService->getModel()->details()['limit']) {
                $collection->shift();
                break;
            }
        }

        return $collection;
    }
}
