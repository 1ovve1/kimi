<?php

namespace App\Models;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundInGivenChatException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * User => Chat map relation
 */
class ChatUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
    ];

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Chat>
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * @return HasMany<ChatMessage>
     */
    public function chat_messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * @throws ChatNotFoundException
     * @throws UserNotFoundException
     * @throws UserNotFoundInGivenChatException
     */
    public static function findFromChatDataAndUserData(ChatData $chatData, UserData $userData): ChatUser
    {
        return Chat::findForChatData($chatData)
            ->chat_users()
            ->where('user_id', User::findForUserData($userData)->id)
            ->first() ?? throw new UserNotFoundInGivenChatException($chatData, $userData);
    }
}
