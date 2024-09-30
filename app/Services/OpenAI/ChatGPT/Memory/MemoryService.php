<?php

declare(strict_types=1);

namespace App\Services\OpenAI\ChatGPT\Memory;

use App\Data\Telegram\Chat\ChatData;
use App\Exceptions\Services\OpenAI\Tokenizer\TokensLimitException;
use App\Repositories\OpenAI\ChatGPT\Memory\MemoryRepositoryInterface;
use App\Services\Abstract\AbstractService;
use App\Services\OpenAI\Tokenizer\TokenizerServiceInterface;
use Illuminate\Support\Collection;

class MemoryService extends AbstractService implements MemoryServiceInterface
{
    public function __construct(
        readonly TokenizerServiceInterface $tokenizerService,
        readonly MemoryRepositoryInterface $memoryRepository
    ) {}

    public function collectMessagesForBoilerplate(ChatData $chatData): Collection
    {
        $collection = new Collection;
        $messages = $this->memoryRepository->getAllLatest($chatData);

        try {
            foreach ($messages as $message) {
                $collection->push($message);

                $this->tokenizerService->tryCount($collection->pluck('content'));
            }
        } catch (TokensLimitException $e) {
            $collection->pop();
        }

        return $collection;
    }
}
