<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\User;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Repositories\Abstract\AbstractRepository;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Telegram\Properties\ChatMemberStatus;
use SergiX44\Nutgram\Telegram\Properties\ChatType;

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

    public function isAdmin(ChatData $chatData, UserData $userData): bool
    {
        return $chatData->target->type === ChatType::PRIVATE || $this->compareStatus($chatData, $userData, ChatMemberStatus::ADMINISTRATOR, ChatMemberStatus::CREATOR);
    }

    /**
     * @throws ChatNotFoundException
     * @throws UserNotFoundException
     */
    private function compareStatus(ChatData $chatData, UserData $userData, ChatMemberStatus ...$chatMemberStatus): bool
    {
        return Chat::findForChatData($chatData)
            ->chat_users()
            ->where('user_id', User::findForUserData($userData)->id)
            ->whereIn('status', $chatMemberStatus)
            ->exists();
    }
}
