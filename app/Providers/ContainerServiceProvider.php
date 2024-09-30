<?php

namespace App\Providers;

use App\Repositories\Abstract\RepositoryFactoryInterface;
use App\Repositories\OpenAI\ChatGPT\Memory\MemoryRepositoryInterface;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\ChatMessage\ChatMessageRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Repositories\Telegram\User\UserRepositoryInterface;
use App\Services\Abstract\ServiceFactoryInterface;
use App\Services\OpenAI\ChatGPT\ChatGPTServiceInterface;
use App\Services\OpenAI\Tokenizer\TokenizerServiceInterface;
use Illuminate\Support\ServiceProvider;

class ContainerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->bindManyServices([
            TokenizerServiceInterface::class,
            ChatGPTServiceInterface::class,
            MemoryRepositoryInterface::class,
        ]);

        $this->bindManyRepositories([
            UserRepositoryInterface::class,
            ChatRepositoryInterface::class,
            TelegramDataRepositoryInterface::class,
            ChatMessageRepositoryInterface::class,
            MemoryRepositoryInterface::class,
        ]);
    }

    /**
     * @param  array<string>  $services
     */
    public function bindManyServices(array $services): void
    {
        foreach ($services as $service) {
            $this->bindService($service);
        }
    }

    public function bindManyRepositories(array $repositories): void
    {
        foreach ($repositories as $repositoryInterfaceName) {
            $this->bindRepository($repositoryInterfaceName);
        }
    }

    /**
     * @param  string  $serviceInterfaceName  - service interface name
     */
    public function bindService(string $serviceInterfaceName): void
    {
        $serviceFactoryClassName = str_replace('Interface', 'Factory', $serviceInterfaceName);
        /** @var ServiceFactoryInterface $serviceFactory */
        $serviceFactory = new $serviceFactoryClassName;

        $this->app->bind($serviceInterfaceName, fn () => $serviceFactory->get());
    }

    /**
     * @param  string  $repositoryInterfaceName  - repository interface name
     */
    public function bindRepository(string $repositoryInterfaceName): void
    {
        $repositoryFactoryClassName = str_replace('Interface', 'Factory', $repositoryInterfaceName);
        /** @var RepositoryFactoryInterface $repositoryFactory */
        $repositoryFactory = new $repositoryFactoryClassName;

        $this->app->bind($repositoryInterfaceName, fn () => $repositoryFactory->get());
    }
}
