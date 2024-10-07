<?php

namespace App\Models;

use App\Data\Telegram\Chat\Types\PrivateData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tg_id',
        'first_name',
        'last_name',
        'username',
        'language_code',
        'is_premium',
        'is_bot',
    ];

    /**
     * @return MorphOne<Chat>
     */
    public function chat(): MorphOne
    {
        return $this->morphOne(Chat::class, 'target');
    }

    /**
     * @throws UserNotFoundException
     */
    static function findForUserData(UserData|PrivateData $userData): User
    {
        return User::whereId($userData->id)
            ->orWhere('tg_id', $userData->tg_id)
            ->first() ?? throw new UserNotFoundException($userData);
    }
}
