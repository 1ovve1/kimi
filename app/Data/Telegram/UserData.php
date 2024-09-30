<?php

namespace App\Data\Telegram;

use App\Models\User;
use Spatie\LaravelData\Data;

/**
 * @link User
 */
class UserData extends Data
{
    public function __construct(
        readonly int $id,
        readonly string $first_name,
        readonly ?string $last_name = null,
        readonly ?string $username = null,
        readonly ?string $language_code = null,
        readonly ?bool $is_premium = null,
        readonly bool $is_bot = false,
    ) {}
}
