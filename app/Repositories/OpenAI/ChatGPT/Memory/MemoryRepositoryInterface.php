<?php

declare(strict_types=1);

namespace App\Repositories\OpenAI\ChatGPT\Memory;

use App\Data\OpenAI\ChatGPT\GPTDialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Repositories\Abstract\RepositoryInterface;
use Illuminate\Support\Collection;

interface MemoryRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection<GPTDialogMessageData>
     */
    public function getAllLatest(ChatData $chatData): Collection;

    public function memorize(ChatMessageData $chatMessageData): ChatMessageData;
}
