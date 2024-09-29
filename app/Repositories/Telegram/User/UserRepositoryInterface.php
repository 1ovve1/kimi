<?php

declare(strict_types=1);

namespace App\Repositories\Telegram\User;

use App\Data\Telegram\UserData;
use App\Repositories\Abstract\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function save(UserData $userData): UserData;
}
