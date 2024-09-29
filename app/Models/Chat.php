<?php

namespace App\Models;

use App\Models\Abstract\Types\ChatInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'target_type',
        'target_id',
    ];

    public $incrementing = false;

    /**
     * @return MorphTo<ChatInterface>
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

    /**
     * @return HasMany<ChatUser>
     */
    public function chat_users(): HasMany
    {
        return $this->hasMany(ChatUser::class);
    }
}
