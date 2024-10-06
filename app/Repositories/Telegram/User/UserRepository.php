<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\User;

use App\Data\Telegram\Chat\ChatMessageData;
use App\Data\Telegram\UserData;
use App\Models\ChatMessage;
use App\Models\User;
use App\Repositories\Abstract\AbstractRepository;
use Illuminate\Support\Facades\Log;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function save(UserData $userData): UserData
    {
        $user = User::whereId($userData->id)->orWhere('tg_id', $userData->tg_id)->first();

        if ($user === null) {
            $user = new User($userData->toArray());
            $user->save();

            Log::info('New user...', $user->toArray());
        }

        return UserData::from($user);
    }

    public function findByMessage(ChatMessageData $chatMessageData): UserData
    {
        /** @var ChatMessage $message */
        $message = ChatMessage::with('chat_user.user')->find($chatMessageData->id);

        $user = $message->chat_user->user;

        return UserData::from($user);
    }
}
