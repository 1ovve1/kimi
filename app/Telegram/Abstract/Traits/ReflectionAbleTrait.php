<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use ReflectionException;
use ReflectionMethod;

trait ReflectionAbleTrait
{
    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    private function callStaticMethodWithArgs(string $methodName, array $additionalParamsArguments = []): mixed
    {
        $reflectionMethod = new ReflectionMethod(static::class, $methodName);
        $parameters = $reflectionMethod->getParameters();

        $app = app();
        $instances = [];
        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType();

            if (array_key_exists($name, $additionalParamsArguments)) {
                $instances[] = $additionalParamsArguments[$name];
            } elseif ($type && ! $type->isBuiltin()) {
                $instances[] = $app->make($type->getName(), $additionalParamsArguments);
            } else {
                $instances[] = null;
            }
        }

        return $reflectionMethod->invokeArgs($this, $instances);
    }
}
