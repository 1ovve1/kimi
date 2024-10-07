<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\User;

use App\Data\Telegram\Chat\ChatMessageData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;
use App\Models\ChatMessage;
use App\Models\User;
use App\Repositories\Abstract\AbstractRepository;
use Illuminate\Support\Facades\Log;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function save(UserData $userData): UserData
    {
        try {
            $user = User::findForUserData($userData);
        } catch (UserNotFoundException $e) {
            $user = User::create($userData->toArray());

            Log::info('New user...', $user->toArray());
        }

        return UserData::from($user);
    }

    public function findByMessage(ChatMessageData $chatMessageData): UserData
    {
        $message = ChatMessage::findForChatMessageData($chatMessageData);

        $user = $message->chat_user->user;

        return UserData::from($user);
    }
}
