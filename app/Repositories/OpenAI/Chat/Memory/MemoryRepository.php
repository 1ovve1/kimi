<?php

declare(strict_types=1);

namespace App\Repositories\OpenAI\Chat\Memory;

use App\Data\OpenAI\Chat\DialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessageNotFoundException;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\OpenaiChatMemory;
use App\Repositories\Abstract\AbstractRepository;
use App\Services\OpenAI\Chat\Enums\DialogRolesEnum;
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

        $messages = ChatMessage::whereHas('chat_user', function (Builder $builder) use ($chat) {
            $builder->where('chat_id', $chat->id);
        })->whereHas('chat_gpt_memory')->latest()->with('chat_user.user', 'chat_gpt_memory')->limit(100)->get();

        $collection = new Collection;

        $messages->map(function (ChatMessage $message) use ($collection) {
            // user that own current message
            $user = $message->chat_user->user;
            // full name of user
            $fullNane = trim("{$user->first_name} {$user->last_name}");
            // formatted message content for the gpt
            $content = "{$fullNane}: {$message->text}";
            // cached tokens count value
            $tokens_count = $message->chat_gpt_memory->tokens_count;
            // resolve role
            $role = $user->is_bot ? DialogRolesEnum::ASSISTANT : DialogRolesEnum::USER;

            $collection->push(new DialogMessageData(
                $content, $role, $tokens_count
            ));
        });

        return $collection;
    }

    public function memorize(ChatMessageData $chatMessageData, int $tokens_count): ChatMessageData
    {
        /** @var ChatMessage $chatMessage */
        $chatMessage = ChatMessage::find($chatMessageData->id) ?? throw new ChatMessageNotFoundException($chatMessageData);

        $chatMessage->chat_gpt_memory()->save(new OpenaiChatMemory(['tokens_count' => $tokens_count]));

        return $chatMessageData;
    }

    /**
     * @throws ChatNotFoundException
     */
    public function deleteAll(ChatData $chatData): int
    {
        /** @var Chat $chat */
        $chat = Chat::find($chatData->id) ?? throw new ChatNotFoundException($chatData);

        return ChatMessage::whereHas('chat_user', function (Builder $builder) use ($chat) {
            $builder->where('chat_id', $chat->id);
        })->delete();
    }
}