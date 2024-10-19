<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat;

use App\Services\Abstract\ServiceFactoryInterface;
use App\Services\OpenAI\Chat\Characters\CharacterInterface;
use App\Services\OpenAI\Chat\Characters\CharactersFactory;
use App\Services\OpenAI\Chat\Enums\ChatModelEnum;
use Illuminate\Support\Facades\App;
use OpenAI;

class ChatServiceFactory implements ServiceFactoryInterface
{
    public function get(array $params = []): ChatServiceInterface
    {
        return $this->getChatWithKimi();
    }

    public function getChatWithKimi(): ChatServiceInterface
    {
        return $this->resolveCharacter(
            (new CharactersFactory(ChatModelEnum::default()))->createKimi()
        );
    }

    private function resolveCharacter(CharacterInterface $character): ChatServiceInterface
    {
        $client = OpenAi::factory()
            ->withApiKey(config('gpt.key'))
            ->make();

        return App::make(ChatServiceWIthEscapePrelude::class, [
            'client' => $client->chat(),
            'character' => $character,
        ]);
    }
}
