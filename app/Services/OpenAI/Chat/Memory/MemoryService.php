<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat\Memory;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Exceptions\Services\OpenAI\Tokenizer\TokensLimitException;
use App\Repositories\OpenAI\Chat\Memory\MemoryRepositoryInterface;
use App\Services\Abstract\AbstractService;
use App\Services\OpenAI\Chat\Tokenizer\TokenizerServiceInterface;
use Illuminate\Support\Collection;

class MemoryService extends AbstractService implements MemoryServiceInterface
{
    public function __construct(
        readonly TokenizerServiceInterface $tokenizerService,
        readonly MemoryRepositoryInterface $memoryRepository
    ) {}

    public function collectMemories(ChatData $chatData): Collection
    {
        $collection = new Collection;
        $messages = $this->memoryRepository->getAllLatest($chatData);

        try {
            foreach ($messages->reverse() as $message) {
                $collection->unshift($message);

                $this->tokenizerService->tryCount($collection->pluck('content'));
            }
        } catch (TokensLimitException) {
            $collection->shift();
        }

        return $collection;
    }

    public function memorize(ChatMessageData $chatData): ChatMessageData
    {
        return $this->memoryRepository->memorize($chatData, $this->tokenizerService->count($chatData->text));
    }

    public function reset(ChatData $chatData): int
    {
        return $this->memoryRepository->deleteAll($chatData);
    }
}
