<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Exceptions\Repositories\Telegram\ChatMessage\ChatMessageAlreadyExistsException;
use App\Exceptions\Repositories\Telegram\ChatMessage\ChatMessageNotFoundException;
use App\Exceptions\Repositories\Telegram\TelegramData\TelegramUserNotFoundException;
use App\Repositories\Telegram\ChatMessage\ChatMessageRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryFactory;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Abstract\AbstractService;
use App\Telegram\Abstract\Keyboards\TelegramKeyboard;
use App\Telegram\Abstract\Keyboards\TelegramKeyboardInterface;
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
        $this->telegramDataRepository = app(TelegramDataRepositoryFactory::class)->getFromNutgram($this->nutgram);
        $this->chatMessageRepository = app(ChatMessageRepositoryInterface::class);
    }

    public function sendMessage(string $content, ?ChatData $chatData = null): ChatMessageData
    {
        $chatData ??= $this->telegramDataRepository->getChat();

        if (self::PARSE_MODE === ParseMode::MARKDOWN) {
            $content = $this->escapeCharactersForMarkdown($content);
        }

        $message = $this->nutgram
            ->sendMessage($content, chat_id: $chatData->target->id, parse_mode: self::PARSE_MODE);

        return ChatMessageData::fromNutgram($message);
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

    public function replyToMessage(string $content, ?ChatMessageData $chatMessageData = null, ?ChatData $chatData = null): ChatMessageData
    {
        try {
            $chatMessageData = $this->chatMessageRepository->find($chatMessageData ?? $this->telegramDataRepository->getMessage());
            $chatData ??= $this->chatMessageRepository->chat($chatMessageData);
        } catch (ChatMessageNotFoundException $e) {
            $chatMessageData = $this->telegramDataRepository->getMessage();
            $chatData = $this->telegramDataRepository->getChat();
        }

        if (self::PARSE_MODE === ParseMode::MARKDOWN) {
            $content = $this->escapeCharactersForMarkdown($content);
        }

        $message = $this->nutgram
            ->sendMessage($content, chat_id: $chatData->target->tg_id, parse_mode: self::PARSE_MODE, reply_to_message_id: $chatMessageData->tg_id);

        return ChatMessageData::fromNutgram($message);
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

    public function sendMessageWithKeyboard(string $content, TelegramKeyboardInterface $telegramKeyboard, ?ChatData $chatData = null): ChatMessageData
    {
        $chatData ??= $this->telegramDataRepository->getChat();

        if (self::PARSE_MODE === ParseMode::MARKDOWN) {
            $content = $this->escapeCharactersForMarkdown($content);
        }

        $message = $this->nutgram
            ->sendMessage($content, chat_id: $chatData->target->id, parse_mode: self::PARSE_MODE, reply_markup: $telegramKeyboard->make());

        return ChatMessageData::fromNutgram($message);
    }

    public function updateKeyboard(TelegramKeyboard $telegramKeyboard, ?ChatMessageData $chatMessageData = null, ?ChatData $chatData = null): void
    {
        try {
            $chatMessageData = $this->chatMessageRepository->find($chatMessageData ?? $this->telegramDataRepository->getMessage());
            $chatData ??= $this->chatMessageRepository->chat($chatMessageData);
        } catch (ChatMessageNotFoundException $e) {
            $chatMessageData = $this->telegramDataRepository->getMessage();
            $chatData = $this->telegramDataRepository->getChat();
        }

        $chatMessageData ??= $this->telegramDataRepository->getMessage();

        $this->nutgram
            ->editMessageReplyMarkup(chat_id: $chatData->target->tg_id, message_id: $chatMessageData->id, reply_markup: $telegramKeyboard->make());
    }

    public function deleteMessage(?ChatMessageData $chatMessageData = null, ?ChatData $chatData = null): void
    {
        try {
            $chatMessageData = $this->chatMessageRepository->find($chatMessageData ?? $this->telegramDataRepository->getMessage());
            $chatData ??= $this->chatMessageRepository->chat($chatMessageData);
        } catch (ChatMessageNotFoundException $e) {
            $chatMessageData = $this->telegramDataRepository->getMessage();
            $chatData = $this->telegramDataRepository->getChat();
        }

        $this->nutgram->deleteMessage(chat_id: $chatData->target->tg_id, message_id: $chatMessageData->tg_id);

        $this->chatMessageRepository->delete($chatMessageData);
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
