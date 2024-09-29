<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatGPTMemory extends Model
{
    use HasFactory;

    protected $table = "chat_gpt_memories";

    protected $fillable = [
        'chat_message_id'
    ];

    /**
     * @return BelongsTo<ChatMessage>
     */
    public function chat_message(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class);
    }
}