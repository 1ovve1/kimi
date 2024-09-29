<?php

namespace App\Models;

use App\Models\Abstract\Types\ChatInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class User extends Model implements ChatInterface
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'username',
        'language_code',
        'is_premium',
        'is_bot',
    ];

    public $incrementing = false;

    /**
     * @return MorphOne<Chat>
     */
    public function chat(): MorphOne
    {
        return $this->morphOne(Chat::class, 'target');
    }

    /**
     * Chat with a user (private)
     */
    public function getChatId(): int
    {
        return $this->id;
    }
}
