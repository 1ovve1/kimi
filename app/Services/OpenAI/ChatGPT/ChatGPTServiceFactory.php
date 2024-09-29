<?php

declare(strict_types=1);

namespace App\Services\OpenAI\ChatGPT;

use App\Services\Abstract\ServiceFactoryInterface;
use Illuminate\Support\Facades\App;
use OpenAI;

class ChatGPTServiceFactory implements ServiceFactoryInterface
{
    public function get(): ChatGPTServiceInterface
    {
        $client = OpenAi::factory()
            ->withApiKey(config('gpt.key'))
            ->make();

        return App::make(ChatGPTService::class, [
            'client' => $client,
        ]);
    }
}
