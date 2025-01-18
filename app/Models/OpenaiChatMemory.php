<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpenaiChatMemory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'chat_message_id',
        'tokens_count',
    ];

    /**
     * @return BelongsTo<ChatMessage>
     */
    public function chat_message(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class);
    }
}
