<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Chat messages heap
 */
class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'chat_user_id',
        'text',
    ];

    protected $casts = [
        'text' => 'encrypted',
    ];

    public $incrementing = false;

    /**
     * @return BelongsTo<ChatUser>
     */
    public function chat_user(): BelongsTo
    {
        return $this->belongsTo(ChatUser::class);
    }
}