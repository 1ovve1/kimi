<?php

namespace App\Models;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessage\ChatMessageNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Chat messages heap
 */
class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tg_id',
        'chat_user_id',
        'text',
        'created_at',
    ];

    protected $casts = [
        'text' => 'encrypted',
    ];

    /**
     * @return BelongsTo<ChatUser>
     */
    public function chat_user(): BelongsTo
    {
        return $this->belongsTo(ChatUser::class);
    }

    /**
     * @return HasOne<OpenaiChatMemory>
     */
    public function chat_gpt_memory(): HasOne
    {
        return $this->hasOne(OpenaiChatMemory::class);
    }

    /**
     * @throws ChatMessageNotFoundException
     */
    public static function findForChatMessageData(ChatMessageData $chatMessageData): ChatMessage
    {
        return ChatMessage::whereId($chatMessageData->id)
            ->first() ?? throw new ChatMessageNotFoundException($chatMessageData);
    }

    /**
     * @throws ChatMessageNotFoundException
     * @throws ChatNotFoundException
     */
    public function findForChatMessageDataAndChatData(ChatMessageData $chatMessageData, ChatData $chatData): ChatMessage
    {
        return Chat::findForChatData($chatData)
            ->messages()
            ->where('tg_id', $chatMessageData->tg_id)
            ->first() ?? throw new ChatMessageNotFoundException($chatMessageData);
    }
}
