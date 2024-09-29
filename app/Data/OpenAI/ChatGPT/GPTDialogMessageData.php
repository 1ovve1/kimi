<?php

namespace App\Data\OpenAI\ChatGPT;

use App\Data\Telegram\Chat\ChatMessageData;
use Spatie\LaravelData\Data;

class GPTDialogMessageData extends Data
{
    public function __construct(
        readonly string $content,
        readonly string $role,
    ) {}
}
