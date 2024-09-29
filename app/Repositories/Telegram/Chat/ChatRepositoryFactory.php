<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\Chat;

use App\Repositories\Abstract\RepositoryFactoryInterface;
use Illuminate\Support\Facades\App;

class ChatRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): ChatRepositoryInterface
    {
        return App::make(ChatRepository::class);
    }
}
