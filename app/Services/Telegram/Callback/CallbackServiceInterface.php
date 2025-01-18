<?php

declare(strict_types=1);

namespace App\Services\Telegram\Callback;

interface CallbackServiceInterface
{
    public function answerCallback(string $text): void;
}
