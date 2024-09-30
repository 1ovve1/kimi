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

        $botMessages = ChatMessage::whereHas('chat_user', function (Builder $builder) use ($chat) {
            $builder->where('chat_id', $chat->id)->whereHas('user', function (Builder $builder) {
                $builder->where('is_bot', true);
            });
        })->whereHas('chat_gpt_memory')->latest()->with('chat_user.user')->get();

        $userMessages = ChatMessage::whereHas('chat_user', function (Builder $builder) use ($chat) {
            $builder->where('chat_id', $chat->id)->whereHas('user', function (Builder $builder) {
                $builder->where('is_bot', false);
            });
        })->whereHas('chat_gpt_memory')->latest()->with('chat_user.user')->get();

        $botMessages->map(function (ChatMessage $message) {
            $user = $message->chat_user->user;
            $fullNane = trim("{$user->first_name} {$user->last_name}");

            $message->content = "{$fullNane}: {$message->text}";
            $message->role = 'assistant';

            return $message;
        });

        $userMessages->map(function (ChatMessage $message) {
            $user = $message->chat_user->user;
            $fullNane = trim("{$user->first_name} {$user->last_name}");

            $message->content = "{$fullNane}: {$message->text}";
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
