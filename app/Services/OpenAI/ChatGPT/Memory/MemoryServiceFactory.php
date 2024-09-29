<?php

declare(strict_types=1);

namespace App\Services\OpenAI\ChatGPT\Memory;

use App\Services\Abstract\ServiceFactoryInterface;
use Illuminate\Support\Facades\App;


class MemoryServiceFactory implements ServiceFactoryInterface
{
    function get(): MemoryServiceInterface
    {
        return App::make(MemoryService::class);
    }
}
