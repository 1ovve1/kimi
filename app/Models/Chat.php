<?php

namespace App\Models;

use App\Data\Telegram\Chat\ChatData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'tg_id',
        'target_type',
        'target_id',
        'interactive_mode',
    ];

    /**
     * @return MorphTo<Channel|Group|Supergroup|User>
     */
    public function target(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasManyThrough<User>
     */
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, ChatUser::class, 'chat_id', 'id', 'id', 'user_id');
    }

    public function messages(): HasManyThrough
    {
        return $this->hasManyThrough(ChatMessage::class, ChatUser::class, 'chat_id', 'chat_user_id', 'id', 'id');
    }

    /**
     * @return HasMany<ChatUser>
     */
    public function chat_users(): HasMany
    {
        return $this->hasMany(ChatUser::class);
    }

    /**
     * @throws ChatNotFoundException
     */
    public static function findForChatData(ChatData $chatData): Chat
    {
        return Chat::whereId($chatData->id)
            ->orWhereHas('target', fn (Builder $builder) => $builder->where('tg_id', $chatData->target->tg_id))
            ->with('target')
            ->first() ?? throw new ChatNotFoundException($chatData);
    }
}
