<?php

declare(strict_types=1);

namespace App\Services\OpenAI\ChatGPT;

use App\Services\Abstract\ServiceFactoryInterface;
use Illuminate\Support\Facades\App;

class ChatGPTServiceFactory implements ServiceFactoryInterface
{
    public function get(): ChatGPTServiceInterface
    {
        return App::make(ChatGPTService::class);
    }
}
