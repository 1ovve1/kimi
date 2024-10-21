<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat;

use App\Services\Abstract\ServiceFactoryInterface;
use App\Services\OpenAI\Chat\Characters\CharacterBuilderFactory;
use App\Services\OpenAI\Chat\Enums\ChatModelEnum;
use Illuminate\Support\Facades\App;
use OpenAI;

class ChatServiceFactory implements ServiceFactoryInterface
{
    public function get(array $params = []): ChatServiceInterface
    {
        $client = OpenAI::factory()
            ->withApiKey(config('gpt.key'))
            ->make();

        return App::make(ChatServiceWIthEscapePrelude::class, [
            'client' => $client->chat(),
            'characterBuilderFactory' => new CharacterBuilderFactory(ChatModelEnum::default()),
        ]);
    }
}
