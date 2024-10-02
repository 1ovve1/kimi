<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat\Tokenizer;

use App\Services\Abstract\ServiceFactoryInterface;
use App\Services\OpenAI\Chat\Enums\ChatModelEnum;

class TokenizerServiceFactory implements ServiceFactoryInterface
{
    public function get(): TokenizerServiceInterface
    {
        return app(TokenizerService::class, [
            'GPTModelsEnum' => ChatModelEnum::default(),
        ]);
    }
}
