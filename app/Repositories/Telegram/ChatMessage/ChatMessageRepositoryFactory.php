<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\ChatMessage;

use App\Repositories\Abstract\RepositoryFactoryInterface;
use Illuminate\Support\Facades\App;

class ChatMessageRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): ChatMessageRepositoryInterface
    {
        return App::make(ChatMessageRepository::class);
    }
}
