<?php

declare(strict_types=1);

namespace App\Services\RSS;

use App\Services\Abstract\ServiceFactoryInterface;
use Illuminate\Support\Facades\App;

class RSSServiceFactory implements ServiceFactoryInterface
{
    public function get(array $params = []): RSSServiceInterface
    {
        return App::make(RSSService::class);
    }
}
