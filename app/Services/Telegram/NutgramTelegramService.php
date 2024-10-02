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
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class NutgramTelegramService extends AbstractService implements TelegramServiceInterface
{
    const PARSE_MODE = ParseMode::MARKDOWN;

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

        if (self::PARSE_MODE === ParseMode::MARKDOWN) {
            $content = $this->escapeCharactersForMarkdown($content);
        }

        $message = $this->nutgram
            ->sendMessage($content, parse_mode: self::PARSE_MODE, reply_to_message_id: $chatMessageData->id);

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

        if (self::PARSE_MODE === ParseMode::MARKDOWN) {
            $content = $this->escapeCharactersForMarkdown($content);
        }

        $message = $this->nutgram
            ->sendMessage($content, chat_id: $chatData->target->id, parse_mode: self::PARSE_MODE);

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

    /**
     * escape '_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!' chars
     */
    protected function escapeCharactersForMarkdown(string $text): string
    {
        return (new Stringable($text))
            ->replace(
                ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'],
                ['\_', '\*', '\[', '\]', '\(', '\)', '\~', '\`', '\>', '\#', '\+', '\-', '\=', '\|', '\{', '\}', '\.', '\!']
            )->value();
    }
}
