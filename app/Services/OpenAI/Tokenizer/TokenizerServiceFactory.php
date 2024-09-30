<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Tokenizer;

use App\Services\Abstract\ServiceFactoryInterface;
use App\Services\OpenAI\AIModelsEnum;

class TokenizerServiceFactory implements ServiceFactoryInterface
{
    public function get(): TokenizerServiceInterface
    {
        return app(TokenizerService::class, [
            'GPTModelsEnum' => AIModelsEnum::default(),
        ]);
    }
}
