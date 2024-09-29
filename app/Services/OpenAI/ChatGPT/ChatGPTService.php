<?php

declare(strict_types=1);

namespace App\Services\OpenAI\ChatGPT;

use App\Data\OpenAI\ChatGPT\GPTDialogMessageData;
use App\Data\Telegram\Chat\ChatData;
use App\Data\Telegram\Chat\ChatMessageData;
use App\Repositories\OpenAI\ChatGPT\Memory\MemoryRepositoryInterface;
use App\Services\Abstract\AbstractService;
use App\Services\OpenAI\ChatGPT\Memory\MemoryService;
use OpenAI\Client;

class ChatGPTService extends AbstractService implements ChatGPTServiceInterface
{
    public function __construct(
        readonly Client $client,

        readonly MemoryService $memoryService,
    )
    {
    }

    public function answer(ChatMessageData $chatMessageData): GPTDialogMessageData
    {
        $response = $this->client->chat()->create([
            'model' => $this->memoryService->tokenizerService->getModel()->value,
            'messages' => [
                new GPTDialogMessageData($chatMessageData->text, 'bot')
            ],
        ]);

        $answer = $response->choices[0]->message;

        return new GPTDialogMessageData($answer->content, $answer->role);
    }

    public function answerWithMemory(ChatData $chatData): GPTDialogMessageData
    {
        $response = $this->client->chat()->create([
            'model' => $this->memoryService->tokenizerService->getModel()->value,
            'messages' => $this->memoryService->collectMessagesForBoilerplate($chatData),
        ]);

        $answer = $response->choices[0]->message;

        return new GPTDialogMessageData($answer->content, $answer->role);
    }

}
