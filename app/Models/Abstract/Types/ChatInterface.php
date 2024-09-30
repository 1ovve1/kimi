<?php

declare(strict_types=1);

namespace App\Models\Abstract\Types;

/**
 * Chat instance interface (abstract for User, Group, Supergroup, Channel models)
 */
interface ChatInterface
{
    /**
     * Return chat id
     */
    public function getChatId(): int;
}
