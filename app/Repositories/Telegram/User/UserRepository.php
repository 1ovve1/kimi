<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\User;

use App\Data\Telegram\UserData;
use App\Models\User;
use App\Repositories\Abstract\AbstractRepository;
use Illuminate\Support\Facades\Log;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function save(UserData $userData): UserData
    {
        $user = User::find($userData->id);

        if ($user === null) {
            $user = new User($userData->toArray());
            $user->save();

            Log::info('New user...', $user->toArray());
        }

        return UserData::from($user);
    }
}
