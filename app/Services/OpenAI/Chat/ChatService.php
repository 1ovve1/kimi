<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat;

use App\Data\OpenAI\Chat\DialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Enums\Models\CharacterEnum;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repositories\Telegram\ChatMessage\ChatMessageNotFoundException;
use App\Repositories\OpenAI\Chat\Character\CharacterRepositoryInterface;
use App\Repositories\Telegram\ChatMessage\ChatMessageRepositoryInterface;
use App\Services\Abstract\AbstractService;
use App\Services\OpenAI\Chat\Characters\CharacterBuilderFactory;
use App\Services\OpenAI\Chat\Enums\DialogRolesEnum;
use App\Services\OpenAI\Chat\Memory\MemoryServiceInterface;
use OpenAI\Contracts\Resources\ChatContract;
use OpenAI\Responses\Chat\CreateResponse as ChatCreateResponse;

class ChatService extends AbstractService implements ChatServiceInterface
{
    public function __construct(
        readonly ChatContract $client,
        readonly CharacterBuilderFactory $characterBuilderFactory,

        readonly MemoryServiceInterface $memoryService,
        readonly ChatMessageRepositoryInterface $chatMessageRepository,
        readonly CharacterRepositoryInterface $characterRepository,
    ) {}

    public function dryAnswer(string $question): DialogMessageData
    {
        $characterBuilder = $this->characterBuilderFactory->makeDefault();

        $payload = $characterBuilder
            ->createRequestBody(DialogMessageData::fromUser($question));

        if (config('app.debug')) {
            return new DialogMessageData("```php\n".print_r($payload, true)."\n```", DialogRolesEnum::ASSISTANT);
        }

        return $this->parseResponse(
            $this->client->create($payload)
        );
    }

    public function answer(ChatData $chatData, ChatMessageData $chatMessageData): DialogMessageData
    {
        try {
            $characterBuilder = $this->characterBuilderFactory->fromCharacterData(
                $this->characterRepository->findForChat($chatData)
            );
        } catch (ChatMessageNotFoundException|ChatNotFoundException $e) {
            $characterBuilder = $this->characterBuilderFactory->makeDefault();
        }

        $payload = $characterBuilder
            ->withMarkdownResponse()
            ->createRequestBody(DialogMessageData::fromChatMessage($chatMessageData));

        if (config('app.debug')) {
            return new DialogMessageData("```php\n".print_r($payload, true)."\n```", DialogRolesEnum::ASSISTANT);
        }

        return $this->parseResponse(
            $this->client->create($payload)
        );
    }

    public function interactiveAnswer(ChatData $chatData): DialogMessageData
    {
        try {
            $characterBuilder = $this->characterBuilderFactory->fromCharacterData(
                $this->characterRepository->findForChat($chatData)
            );
        } catch (ChatNotFoundException $e) {
            $characterBuilder = $this->characterBuilderFactory->makeDefault();
        }

        $memories = $this->memoryService->collectMemories($chatData);
        $payload = $characterBuilder
            ->withInteractiveMode()
            ->withMarkdownResponse()
            ->createRequestBody(...$memories);

        if (config('app.debug')) {
            return new DialogMessageData("```php\n".print_r($payload, true)."\n```", DialogRolesEnum::ASSISTANT);
        }

        return $this->parseResponse(
            $this->client->create($payload)
        );
    }

    public function experimental(ChatMessageData $chatMessageData): DialogMessageData
    {
        $characterBuilder = $this->characterBuilderFactory->fromCharacterData(
            $this->characterRepository->findByEnum(CharacterEnum::DEFAULT)
        );

        $payload = $characterBuilder->withGodMode()
            ->createRequestBody(DialogMessageData::fromChatMessage($chatMessageData));

        if (config('app.debug')) {
            return new DialogMessageData("```php\n".print_r($payload, true)."\n```", DialogRolesEnum::ASSISTANT);
        }

        return $this->parseResponse(
            $this->client->create($payload)
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
