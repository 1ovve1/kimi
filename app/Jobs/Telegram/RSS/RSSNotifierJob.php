<?php

namespace App\Jobs\Telegram\RSS;

use App\Data\RSS\RSSItemData;
use App\Enums\Models\CharacterEnum;
use App\Exceptions\Repositories\Telegram\ChatMessage\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Exceptions\Repository\OpenAI\Character\CharacterNotFoundException;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Services\OpenAI\Chat\ChatServiceInterface;
use App\Services\RSS\RSSServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use Cache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Lowel\Rss\RssException;
use Psr\SimpleCache\InvalidArgumentException;

class RSSNotifierJob implements ShouldQueue
{
    use Queueable;

    const CACHE_KEY = 'rss.feed';

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @throws CharacterNotFoundException
     * @throws ChatMessageAlreadyExistsException
     * @throws InvalidArgumentException
     * @throws RssException
     * @throws TelegramUserNotFoundException
     */
    public function handle(
        ChatRepositoryInterface $chatRepository,
        RSSServiceInterface $rssService,
        ChatServiceInterface $chatService,
        TelegramServiceInterface $telegramService,
    ): void {
        $chatListForRss = $chatRepository->getAllRssChats();

        if ($chatListForRss->isEmpty()) {
            return;
        }

        $oldLatestNews = Cache::get(self::CACHE_KEY, '');
        $feed = $rssService->getRSSFeed();
        /** @var RSSItemData $latestNews */
        $latestNews = $feed->items[0];

        if (is_string($oldLatestNews) && $oldLatestNews === $latestNews->toJson()) {
            return;
        }

        Cache::set(self::CACHE_KEY, $latestNews->toJson());

        $answer = $chatService->dryAnswer(sprintf("%s\n%s\n\n%s", __('openai.chat.prompts.html'), __('openai.chat.prompts.rss'), $latestNews->fullText()), CharacterEnum::KIMI);

        foreach ($chatListForRss->chunk(config('telegram.limitations.messages.throttle')) as $chatChunk) {
            foreach ($chatChunk as $chat) {
                $telegramService->sendMessageAndSave($answer->content, $chat);
            }

            sleep(1);
        }
    }
}
