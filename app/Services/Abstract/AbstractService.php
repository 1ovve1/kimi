<?php

declare(strict_types=1);

namespace App\Services\Abstract;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\App;
use ReflectionClass;
use RuntimeException;

abstract class AbstractService implements Arrayable, ServiceInterface
{
    private function getStaticInterface(): string
    {
        $reflection = new ReflectionClass(static::class);

        foreach ($reflection->getInterfaceNames() as $interface) {
            if (str_contains($interface, 'ServiceInterface')) {
                return $interface;
            }
        }

        throw new RuntimeException('cannot find interface instance for service '.static::class);
    }

    public function toArray()
    {
        return [];
    }

    public function fromArray(array $array): static
    {
        $class = new ReflectionClass(static::class);

        foreach ($class->getInterfaceNames() as $interface) {
            if (str_contains($interface, 'ServiceInterface')) {
                return App::make($interface);
            }
        }

        throw new RuntimeException('cannot find interface instance for service '.static::class);
    }
}
