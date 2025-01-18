<?php

declare(strict_types=1);

namespace App\Services\Abstract;

use Illuminate\Contracts\Container\BindingResolutionException;

interface ServiceFactoryInterface
{
    /**
     * Return service instance
     *
     * @throws BindingResolutionException
     */
    public function get(array $params = []): object;
}
