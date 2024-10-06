<?php

declare(strict_types=1);

namespace App\Services\Telegram\Callback;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Abstract\AbstractService;
use SergiX44\Nutgram\Nutgram;

class NutgramCallbackService extends AbstractService implements CallbackServiceInterface
{
    protected TelegramDataRepositoryInterface $telegramDataRepository;


    public function __construct(
        protected Nutgram $nutgram
    )
    {
    }

    public function answerCallback(string $text): void
    {
        $this->nutgram->answerCallbackQuery(text: $text);
    }
}
