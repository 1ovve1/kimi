<?php

namespace App\Providers;

use App\Repositories\Abstract\RepositoryFactoryInterface;
use App\Repositories\OpenAI\Chat\Character\CharacterRepositoryInterface;
use App\Repositories\OpenAI\Chat\Memory\MemoryRepositoryInterface;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Repositories\Telegram\ChatMessage\ChatMessageRepositoryInterface;
use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Repositories\Telegram\User\UserRepositoryInterface;
use App\Services\Abstract\ServiceFactoryInterface;
use App\Services\OpenAI\Chat\ChatServiceInterface as OpenAIChatServiceInterface;
use App\Services\OpenAI\Chat\Memory\MemoryServiceInterface as OpenAIChatMemoryServiceInterface;
use App\Services\OpenAI\Chat\Tokenizer\TokenizerServiceInterface as OpenAIChatTokenizerServiceInterface;
use App\Services\Telegram\Callback\CallbackServiceInterface as TelegramCallbackServiceInterface;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use Illuminate\Support\ServiceProvider;

class ContainerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->bindManyServices([
            TelegramServiceInterface::class,
            OpenAIChatServiceInterface::class,
            OpenAIChatMemoryServiceInterface::class,
            OpenAIChatTokenizerServiceInterface::class,
            TelegramDataServiceInterface::class,
            TelegramCallbackServiceInterface::class,
        ]);

        $this->bindManyRepositories([
            UserRepositoryInterface::class,
            ChatRepositoryInterface::class,
            TelegramDataRepositoryInterface::class,
            ChatMessageRepositoryInterface::class,
            MemoryRepositoryInterface::class,
            CharacterRepositoryInterface::class,
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

        $this->app->bind($serviceInterfaceName, fn ($_, $params) => $serviceFactory->get($params));
    }

    /**
     * @param  string  $repositoryInterfaceName  - repository interface name
     */
    public function bindRepository(string $repositoryInterfaceName): void
    {
        $repositoryFactoryClassName = str_replace('Interface', 'Factory', $repositoryInterfaceName);
        /** @var RepositoryFactoryInterface $repositoryFactory */
        $repositoryFactory = new $repositoryFactoryClassName;

        $this->app->bind($repositoryInterfaceName, fn ($_, $params) => $repositoryFactory->get($params));
    }
}
