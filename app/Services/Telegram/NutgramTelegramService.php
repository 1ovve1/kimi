<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Exceptions\Repositories\Telegram\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Repositories\Telegram\ChatMessage\ChatMessageRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryFactory;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Abstract\AbstractService;
use SergiX44\Nutgram\Nutgram;

class NutgramTelegramService extends AbstractService implements TelegramServiceInterface
{
    private readonly TelegramDataRepositoryInterface $telegramDataRepository;

    private readonly ChatMessageRepositoryInterface $chatMessageRepository;

    public function __construct(
        readonly private Nutgram $nutgram,
    ) {
        $this->telegramDataRepository = app(TelegramDataRepositoryFactory::class)->fromNutgram($this->nutgram);
        $this->chatMessageRepository = app(ChatMessageRepositoryInterface::class);
    }

    public function replyToMessage(string $content, ?ChatMessageData $chatMessageData = null): ChatMessageData
    {
        if ($chatMessageData === null) {
            $chatMessageData = $this->telegramDataRepository->getMessage();
        }

        $message = $this->nutgram
            ->sendMessage($content, reply_to_message_id: $chatMessageData->id);

        return ChatMessageData::fromNutgramMessage($message);
    }

    /**
     * @throws ChatMessageAlreadyExistsException
     * @throws TelegramUserNotFoundException
     */
    public function replyToMessageAndSave(string $content, ?ChatMessageData $chatMessageData = null): ChatMessageData
    {
        $chatMessageData = $this->replyToMessage($content, $chatMessageData);

        return $this->chatMessageRepository->save(
            $this->telegramDataRepository->getChat(),
            $this->telegramDataRepository->getUser(),
            $chatMessageData
        );
    }

    public function sendMessage(string $content, ?ChatData $chatData = null): ChatMessageData
    {
        if ($chatData === null) {
            $chatData = $this->telegramDataRepository->getChat();
        }

        $message = $this->nutgram
            ->sendMessage($content, chat_id: $chatData->target->id);

        return ChatMessageData::fromNutgramMessage($message);
    }

    /**
     * @throws ChatMessageAlreadyExistsException
     * @throws TelegramUserNotFoundException
     */
    public function sendMessageAndSave(string $content, ?ChatData $chatData = null): ChatMessageData
    {
        $chatMessage = $this->sendMessage($content, $chatData);

        return $this->chatMessageRepository->save(
            $chatData,
            $this->telegramDataRepository->getUser(),
            $chatMessage
        );
    }
}
