<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat;

use App\Data\OpenAI\Chat\DialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Services\Abstract\AbstractService;
use App\Services\OpenAI\Chat\Characters\CharacterInterface;
use App\Services\OpenAI\Chat\Enums\DialogRolesEnum;
use App\Services\OpenAI\Chat\Memory\MemoryServiceInterface;
use OpenAI\Contracts\Resources\ChatContract;
use OpenAI\Responses\Chat\CreateResponse as ChatCreateResponse;

class ChatService extends AbstractService implements ChatServiceInterface
{
    public function __construct(
        readonly ChatContract $client,
        readonly CharacterInterface $character,

        readonly MemoryServiceInterface $memoryService
    ) {}

    public function answer(ChatMessageData $chatMessageData): DialogMessageData
    {
        return $this->parseResponse(
            $this->client->create($this->character->createRequestBody(DialogMessageData::fromChatMessage($chatMessageData)))
        );
    }

    public function interactiveAnswer(ChatData $chatData): DialogMessageData
    {
        $memories = $this->memoryService->collectMemories($chatData);

        return $this->parseResponse(
            $this->client->create($this->character->createRequestBody(...$memories))
        );
    }

    protected function parseResponse(ChatCreateResponse $response): DialogMessageData
    {
        return new DialogMessageData(
            $response->choices[0]->message->content,
            DialogRolesEnum::ASSISTANT
        );
    }
}
