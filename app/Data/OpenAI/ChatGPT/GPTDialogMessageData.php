<?php

namespace App\Data\OpenAI\ChatGPT;

use Spatie\LaravelData\Data;

class GPTDialogMessageData extends Data
{
    public function __construct(
        readonly string $content,
        readonly string $role,
    ) {}
}
