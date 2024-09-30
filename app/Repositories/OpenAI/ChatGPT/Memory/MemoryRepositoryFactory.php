<?php

declare(strict_types=1);

namespace App\Repositories\OpenAI\ChatGPT\Memory;

use App\Repositories\Abstract\RepositoryFactoryInterface;
use Illuminate\Support\Facades\App;

class MemoryRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): MemoryRepositoryInterface
    {
        return App::make(MemoryRepository::class);
    }
}
