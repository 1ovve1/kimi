<?php

declare(strict_types=1);

namespace App\Services\Telegram\TelegramData;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Data\Telegram\UserData;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessage\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\ReplyWasNotFoundedException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Exceptions\Repositories\Telegram\User\UserNotFoundException;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\ChatMessage\ChatMessageRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryFactory;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Repositories\Telegram\User\UserRepositoryInterface;
use App\Services\Abstract\AbstractService;
use App\Services\OpenAI\Chat\Memory\MemoryServiceInterface;
use Illuminate\Support\Facades\App;
use SergiX44\Nutgram\Nutgram;

class NutgramTelegramDataService extends AbstractService implements TelegramDataServiceInterface
{
    public readonly TelegramDataRepositoryInterface $telegramDataRepository;

    public function __construct(
        readonly Nutgram $nutgram,

        readonly MemoryServiceInterface $memoryService,
        readonly ChatRepositoryInterface $chatRepository,
        readonly ChatMessageRepositoryInterface $chatMessageRepository,
        readonly UserRepositoryInterface $userRepository,
    ) {
        $this->telegramDataRepository = App::make(TelegramDataRepositoryFactory::class)->getFromNutgram($this->nutgram);
    }

    public function storeAllTelegramDataInDb(): void
    {
        // save chat instance
        $chat = $this->resolveChat();

        try {
            // save bot user instance in db
            $this->resolveMe();

            // save user instance in db
            $this->resolveUser();

            // save reply user if exists
            try {
                $this->resolveUserReply();
            } catch (ReplyWasNotFoundedException|UserNotFoundException $e) {
            }

            // if interactive mode are enable we also save the messages
            if ($chat->interactive_mode) {
                $message = $this->resolveMessage();
                $this->memoryService->memorize($message);

                $replyMessage = $this->resolveReplyMessage();
                $this->memoryService->memorize($replyMessage);
            }
        } catch (UserNotFoundException|ChatMessageAlreadyExistsException|ChatNotFoundException|ReplyWasNotFoundedException|TelegramUserNotFoundException $e) {
        }
    }

    public function storeChatAndUsersInDb(): void
    {
        // save chat instance
        $this->resolveChat();

        try {
            // save bot user instance in db
            $this->resolveMe();

            // save user instance in db
            $this->resolveUser();

            // save reply user if exists
            try {
                $this->resolveUserReply();
            } catch (ReplyWasNotFoundedException|UserNotFoundException $e) {
            }
        } catch (ChatNotFoundException|UserNotFoundException $e) {
            // do nothing
        }
    }

    public function resolveChat(): ChatData
    {
        return $this->chatRepository->save(
            $this->telegramDataRepository->getChat()
        );
    }

    public function resolveUser(): UserData
    {
        $chat = $this->resolveChat();

        $me = $this->userRepository->save(
            $this->telegramDataRepository->getUser()
        );

        return $this->chatRepository->appendUser($chat, $me);
    }

    public function resolveMe(): UserData
    {
        $chat = $this->resolveChat();

        $me = $this->userRepository->save(
            $this->telegramDataRepository->getMe()
        );

        return $this->chatRepository->appendUser($chat, $me);
    }

    public function resolveUserReply(): UserData
    {
        $chat = $this->resolveChat();

        $user = $this->userRepository->save(
            $this->telegramDataRepository->getUserReply()
        );

        return $this->chatRepository->appendUser($chat, $user);
    }

    public function resolveReplyMessage(): ChatMessageData
    {
        $chat = $this->resolveChat();
        $user = $this->resolveUserReply();
        $message = $this->telegramDataRepository->getReplyMessage();

        return $this->chatMessageRepository->save($chat, $user, $message);
    }

    public function resolveMessage(): ChatMessageData
    {
        $chat = $this->resolveChat();
        $user = $this->resolveUser();
        $message = $this->telegramDataRepository->getMessage();

        try {
            $reply = $this->resolveReplyMessage();
        } catch (ChatMessageAlreadyExistsException|ChatNotFoundException|ReplyWasNotFoundedException|UserNotFoundException $e) {
            $reply = null;
        }

        return $this->chatMessageRepository->save($chat, $user, $message, $reply);
    }
}
