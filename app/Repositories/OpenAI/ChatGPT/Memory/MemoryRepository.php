<?php

declare(strict_types=1);

namespace App\Repositories\OpenAI\ChatGPT\Memory;

use App\Data\OpenAI\ChatGPT\GPTDialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessageNotFoundException;
use App\Models\Chat;
use App\Models\ChatGPTMemory;
use App\Models\ChatMessage;
use App\Repositories\Abstract\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MemoryRepository extends AbstractRepository implements MemoryRepositoryInterface
{
    /**
     * @throws ChatNotFoundException
     */
    public function getAllLatest(ChatData $chatData): Collection
    {
        /** @var Chat $chat */
        $chat = Chat::find($chatData->id) ?? throw new ChatNotFoundException($chatData);

        $botMessages = $chat->whereHas('chat_users', function (Builder $builder) {
            $builder->whereHas('chat_messages', function (Builder $builder) {
                $builder->has('chat_gpt_memory');
            })->whereHas('user', function (Builder $builder) {
                $builder->where('is_bot', true);
            });
        })->latest()->get();

        $userMessages = $chat->whereHas('chat_users', function (Builder $builder) {
            $builder->whereHas('chat_messages', function (Builder $builder) {
                $builder->has('chat_gpt_memory');
            })->whereHas('user', function (Builder $builder) {
                $builder->where('is_bot', false);
            });
        })->latest()->get();

        $botMessages->map(function (ChatMessage $message) {
            $message->content = $message->text;
            $message->role = 'assistant';

            return $message;
        });

        $userMessages->map(function (ChatMessage $message) {
            $message->content = $message->text;
            $message->role = 'user';

            return $message;
        });

        $collection = $botMessages->merge($userMessages)->sortBy('created_at');

        return new Collection(GPTDialogMessageData::collect($collection));
    }

    public function memorize(ChatMessageData $chatMessageData): ChatMessageData
    {
        /** @var ChatMessage $chatMessage */
        $chatMessage = ChatMessage::find($chatMessageData->id) ?? throw new ChatMessageNotFoundException($chatMessageData);

        $chatMessage->chat_gpt_memory()->save(new ChatGPTMemory);

        return $chatMessageData;
    }
}
