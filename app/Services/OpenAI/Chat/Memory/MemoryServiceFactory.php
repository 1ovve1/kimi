<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat\Memory;

use App\Services\Abstract\ServiceFactoryInterface;
use Illuminate\Support\Facades\App;

class MemoryServiceFactory implements ServiceFactoryInterface
{
    public function get(): MemoryServiceInterface
    {
        return App::make(CachedMemoryService::class);
    }
}
