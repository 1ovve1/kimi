<?php

declare(strict_types=1);

namespace App\Repositories\OpenAI\Chat\Memory;

use App\Data\OpenAI\Chat\DialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Repositories\Abstract\RepositoryInterface;
use Illuminate\Support\Collection;

interface MemoryRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection<DialogMessageData>
     *
     * @throws ChatNotFoundException
     */
    public function getAllLatest(ChatData $chatData): Collection;

    public function memorize(ChatMessageData $chatMessageData, int $tokens_count): ChatMessageData;

    public function deleteAll(ChatData $chatData): int;

    public function count(ChatData $chatData): int;
}
