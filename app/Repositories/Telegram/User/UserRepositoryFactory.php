<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\User;

use App\Repositories\Abstract\RepositoryFactoryInterface;

class UserRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): UserRepositoryInterface
    {
        return app(UserRepository::class);
    }
}
