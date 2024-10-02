<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat\Enums;

enum DialogRolesEnum: string
{
    case ASSISTANT = 'assistant';
    case USER = 'user';
    case SYSTEM = 'system';
}
