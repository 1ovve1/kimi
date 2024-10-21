<?php

declare(strict_types=1);

namespace App\Repositories\OpenAI\Chat\Character;

use App\Repositories\Abstract\RepositoryFactoryInterface;
use Illuminate\Support\Facades\App;


class CharacterRepositoryFactory implements RepositoryFactoryInterface
{
    function get(): CharacterRepositoryInterface
    {
        return App::make(CharacterRepository::class);
    }
}
