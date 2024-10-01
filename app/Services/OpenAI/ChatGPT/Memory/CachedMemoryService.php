<?php

declare(strict_types=1);

namespace App\Services\OpenAI\ChatGPT\Memory;

use App\Data\OpenAI\ChatGPT\GPTDialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use Illuminate\Support\Collection;

class CachedMemoryService extends MemoryService implements MemoryServiceInterface
{
    public function collectMessagesForBoilerplate(ChatData $chatData): Collection
    {
        $collection = new Collection;
        $messages = $this->memoryRepository->getAllLatest($chatData);

        $tokensBuffer = 0;

        /** @var GPTDialogMessageData $message */
        foreach ($messages as $message) {
            $collection->push($message);

            $tokensBuffer += $message->tokens_count ?? $this->tokenizerService->count($message->content);

            if ($tokensBuffer > $this->tokenizerService->getModel()->details()['limit']) {
                $collection->pop();
                break;
            }
        }

        return $collection;
    }
}
