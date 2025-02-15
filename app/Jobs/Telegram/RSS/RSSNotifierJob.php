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

        $oldLatestNewsPubDate = Cache::get(self::CACHE_KEY, '');
        $feed = $rssService->getRSSFeed();
        /** @var RSSItemData $latestNews */
        $latestNews = $feed->items[0];

        if (is_string($oldLatestNewsPubDate) && $oldLatestNewsPubDate == $latestNews->pubDate) {
            return;
        }

        Cache::set(self::CACHE_KEY, $latestNews->pubDate);

        $answer = $chatService->dryAnswer(sprintf("%s\n%s\n\n%s", __('openai.chat.prompts.html'), __('openai.chat.prompts.rss'), strip_tags($latestNews->fullText(), '<a>')), CharacterEnum::KIMI);

        if (str_starts_with('Извини', $answer->content)) {
            $answer = $chatService->dryAnswer(sprintf("%s\n%s\n\n%s", __('openai.chat.prompts.html'), __('openai.chat.prompts.rss'), strip_tags($latestNews->fullText(), '<a>')), CharacterEnum::KIMI);
        }

        if (str_starts_with('Извини', $answer->content)) {
            return;
        }

        foreach ($chatListForRss->chunk(config('telegram.limitations.messages.throttle')) as $chatChunk) {
            foreach ($chatChunk as $chat) {
                $telegramService->sendMessageAndSave("{$answer->content}\n\n@kimi_news", $chat);
            }

            sleep(1);
        }
    }
}
