# Предисловие

Данный бот разработан исключительно для веселья. Разработчики полные психи и не умеют думать критически: видим сервис - внедряем фан контент.

# Содержание

* [Установка](#установка)
    + [Docker](#docker)
    + [Переменные среды](#переменные-среды)
    + [Запуск](#запуск)
* [Архитектура](#архитектура)
    + [База данных](#база-данных)
    + [Repositories](#repositories)
      + [Data-объекты](#data-объекты)
    + [Services](#services)
    + [ContainerServiceProvider](#containerserviceprovider)
    + [Telegram](#telegram)
        + [Actions](#actions)
        + [Middlewares](#middlewares)
        + [Commands](#commands)

# [Установка](#содержание)

Проект содержит в себе docker-окружение, его использование обязательно, т.к. оно унифицировано и используется в том числе на удалённом сервере.

## [Docker](#содержание)

Docker-окружение содержит в себе базовый набор из контейнеров:
- Nginx;
- Mysql (9.0.1);
- FPM (8.3);
- PHP-CLI (для исполнения служебных скриптов);
- Adminer.

Они описаны в **docker/docker-compose.yml** файле, более детальная настройка находиться в директориях, соответствующих именованию:

+ **/docker/**
  + **/fpm/**
    + **/config/** - *конфигурационные файлы для PHP-FPM*
    + **/storage/** - *хранилище (логи)*
    + **/Dockerfile**  
  + **/mysql/**
    + **/config/** - *конфигурация базы данных*
    + **/storage/** - *хранилище (логи)*
    + **Dockerfile**
  + **/nginx/**
    + **/config/** - *конфигурация сервера*
    + **/storage/** - *хранилище (логи)*
    + **/Dockerfile**  
  + **/php/**
    + **/scripts/** - *служебные скрипты (загрузка пакетов из composer, запуск миграция и т.п.)*
    + **Dockerfile**
  + **/docker-compose.yml**
  + **/run.sh** - *скрипт для запуска окружения*
  + **/.env.example** - *переменные среды*

## [Переменные среды](#содержание)

```env
# порты для nginx
NGINX_HTTP=8000
NGINX_HTTPS=8433

# подгрузка xdebug в процессе запуска FPM
FPM_XDEBUG=true

# параметры к базе данных
MYSQL_ROOT_PASSWORD=
MYSQL_DATABASE=
MYSQL_USER=
MYSQL_PASSWORD=

# кастомизация путей к хранилищам контейнеров
STORAGE_MYSQL=./mysql/storage
STORAGE_NGINX=./nginx/storage
STORAGE_FPM=./fpm/storage
# путь к проекту (на случай изоляции докер окружения)
STORAGE_APP=./..

# порт для работы adminer
ADMINER_PORT=8080
```

## [Запуск](#содержание)

Для запуска достаточно скопировать .env.example и указать свои данные для базы данных (по желаю можно также сменить порты для nginx):

```bash
cd docker/
cp .env.example .env
```

После этого требуется запустить скрипт **docker/run.sh**:

```bash
bash ./run.sh
```

# [Архитектура](#содержание)

Проект основан на Laravel 11.

Далее перечислены основные архитектурные паттерны, которые активно применяются в проекте.

## [База данных](#содержание)

База данных во многом отвечает структуре ответов Telegram API. С её структурой можно ознакомиться через сервис [draw,io](https://app.diagrams.net/), открыв в нем файл **/docs/scheme/database_diagram.drawio**.

Для миграций и запросов используются стандартные средства Eloquent.

## [Repositories](#содержание)

Все взаимодействия с базой данных сконцентрированны в файлах-репозиториях. Каждый репозиторий обязательно состоит из:

* Интерфейса (ExampleRepositoryInterface);
* Реализации (ExampleRepository);
* Фабрики (ExampleRepositoryFactory).

Репозитории можно генерировать командной:

```bash
php artisan make:repository SubDur/RepositoryName
```

После этого действия будут сгенерированны файлы:

+ app/Repositories/SubDir/RepositoryNameRepositoryInterface.php;

```php
<?php

declare(strict_types=1);

namespace App\Repositories\SubDur\RepositoryName;

use App\Repositories\Abstract\RepositoryInterface;

interface RepositoryNameRepositoryInterface extends RepositoryInterface
{
    //...
}
```

+ app/Repositories/SubDir/RepositoryNameRepository.php

```php
<?php

declare(strict_types=1);

namespace App\Repositories\SubDur\RepositoryName;

use App\Repositories\Abstract\AbstractRepository;

class RepositoryNameRepository extends AbstractRepository implements RepositoryNameRepositoryInterface
{
    //...
}

```

+ app/Repositories/SubDir/RepositoryNameRepositoryFactory.php

```php

<?php

declare(strict_types=1);

namespace App\Repositories\SubDur\RepositoryName;

use App\Repositories\Abstract\RepositoryFactoryInterface;
use Illuminate\Support\Facades\App;


class RepositoryNameRepositoryFactory implements RepositoryFactoryInterface
{
    function get(): RepositoryNameRepositoryInterface
    {
        return App::make(RepositoryNameRepository::class);
    }
}

```

Дальнейшее взаимодействие сводится к созданию методов в файле-интерфейсе и их последующей реализации. Также не стоит забывать подключить созданный репозиторий к [сервис контейнеру](#containerserviceprovider) для доступа через [laravel DI](https://laravel.com/docs/11.x/container).

### [Data-объекты](#содержание)

ОЧЕНЬ ВАЖНО не допускать использование Eloquent моделей вне реализаций репозитория. Данные следует передавать строго через DTO сущности, для этого в проект включен пакет [spatie/laravel-data](https://github.com/spatie/laravel-data).

Использование DTO сильно помогает в контексте работы с Telegram API, т.к. мы будем встречать данные из разных источников и не все из них присутствуют в базе данных.

## [Services](#содержание)

Невозможно реализовать сложную бизнес логику в одном обработчике. Хотя бы с точки зрения совести :)

Поэтому сложные участки бизнес логики, которые требуют переиспользования и работают как самостоятельные единицы, взаимодействуют с базой данных (репозиториями) и могут иметь различные реализации следует выделять в отдельные сервисы.

Структура сервиса во много идентична репозиторию:

* Интерфейса (ExampleServiceInterface);
* Реализации (ExampleService);
* Фабрики (ExampleServiceFactory).

По аналогии с репозиториями, сервисы можно генерировать командной:

```bash
php artisan make:repository SubDur/ServiceName
```

После этого действия будут сгенерированны файлы:

+ app/Services/SubDir/ServiceNameServiceInterface.php;

```php
<?php

declare(strict_types=1);

namespace App\Services\SubDur\ServiceName;

interface ServiceNameServiceInterface
{
    //...
}

```

+ app/Services/SubDir/ServiceNameService.php

```php
<?php

declare(strict_types=1);

namespace App\Services\SubDur\ServiceName;

use App\Services\Abstract\AbstractService;

class ServiceNameService extends AbstractService implements ServiceNameServiceInterface
{
    //...
}


```

+ app/Services/SubDir/ServiceNameServiceFactory.php

```php

<?php

declare(strict_types=1);

namespace App\Services\SubDur\ServiceName;

use App\Services\Abstract\ServiceFactoryInterface;
use Illuminate\Support\Facades\App;


class ServiceNameServiceFactory implements ServiceFactoryInterface
{
    function get(): ServiceNameServiceInterface
    {
        return App::make(ServiceNameService::class);
    }
}


```

Дальнейшее взаимодействие сводится к созданию методов в файле-интерфейсе и их последующей реализации. Также не стоит забывать подключить созданный сервис к [сервис контейнеру](#containerserviceprovider) для доступа через [laravel DI](https://laravel.com/docs/11.x/container).


## [ContainerServiceProvider](#содержание)

DI-контейнеры, на подобие фабрикам, служат для удобной и компактной инициализации объектов с возможностью редактировать подключаемые зависимости (полиморфизм).

В проекте присутствует файл **app/Providers/ContainerServiceProvider.php**, в котором происходит определения создания объектов. В частности он создан для подключения созданных нами репозиториев и сервисов, но этим не ограничивается. Вполне реально включить туда свою реализацию интерфейса для какого-либо объекта и использовать её в своих (надеюсь, благих) целях.

```php
<?php

namespace App\Providers;

use App\Repositories\Abstract\RepositoryFactoryInterface;
use App\Services\Abstract\ServiceFactoryInterface;
use Illuminate\Support\ServiceProvider;

class ContainerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->bindManyServices([
            // здесь располагаются интерфейсы сервисов
            // добавив сюда имя интерфейса, DI  будет
            // использовать дефолтный метод get()
            // из фабрики этого сервиса
        ]);

        $this->bindManyRepositories([
            // здесь располагаются интерфейсы репозиториев
            // добавив сюда имя интерфейса, DI будет
            // использовать дефолтный метод get()
            // из фабрики этого репозитория
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

```

## [Telegram](#содержание)

Проект базируется на библиотеке [nutgram](https://github.com/nutgram/nutgram), а точнее его [laravel адаптере](https://github.com/nutgram/laravel). Но из-за ряда архитектурных ограничений, было принято решение сформировать собственную оболочку над этой библиотекой.

На данный момент не рекомендуется пользоваться командами для генерации файлов (nutgram:make:action, nutgram:make:middleware и nutgram:make:command) из этой библиотеки, т.к. каждый файл теперь использует собственную абстракцию.

### [Actions](#содержание)

События - главное звено в обработке запросов со стороны телеграмма. Они должны располагаться по пути **app/Telegram/Actions**. Все они должны наследовать класс **App\Telegram\Abstract\Actions\AbstractTelegramAction** и определять метод **handle**:

```php
<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Actions;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\TelegramServiceInterface;

interface TelegramActionInterface
{
    /**
     * @param TelegramServiceInterface $telegramService - service for interacting with telegram api
     * @param TelegramDataRepositoryInterface $telegramDataRepository - repository that contains telegram request information
     */
    public function handle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void;
}
```

### [Middlewares](#содержание)

Промежуточные оболочки служат для обертывания событий или команд. Они располагаются по пути **app/Telegram/Middlewares**. Все они должны наследовать класс **App\Telegram\Abstract\Middlewares\AbstractTelegramMiddleware** и определять метод **handle**:

```php
<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Middlewares;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\TelegramServiceInterface;

interface TelegramMiddlewareInterface {
    /**
     * @param TelegramServiceInterface $telegramService - service for interact with the telegram api
     * @param TelegramDataRepositoryInterface $telegramDataRepository - repository that contains telegram request information
     * @param callable $next - action callback
     * @return void
     */
    public function handle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository, callable $next): void;
}
```

### [Commands](#содержание)

Сообщения помогают определять команды телеграмм бота. Они располагаются по пути **app/Telegram/Commands** и должны быть унаследованы от абстракции **App\Telegram\Abstract\Commands\AbstractTelegramCommand**.

Команда должна определять метод **onHandle** и поля:

```php
protected string $command = 'start';

protected ?string $description = 'start kimi';
```

```php
<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Commands;

use App\Repositories\Telegram\TelegramData\TelegramDataRepositoryInterface;
use App\Services\Telegram\TelegramServiceInterface;

/**
 * @phpstan-require-extends AbstractTelegramCommand
 */
interface TelegramCommandInterface
{
    /**
     * @param TelegramServiceInterface $telegramService - service for interact with the telegram api
     * @param TelegramDataRepositoryInterface $telegramDataRepository - repository that contains telegram request information
     * @return void
     */
    public function onHandle(TelegramServiceInterface $telegramService, TelegramDataRepositoryInterface $telegramDataRepository): void;
}
```
