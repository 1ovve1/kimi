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
        readonly ?int $id,
        readonly int $tg_id,
        readonly string $first_name,
        readonly ?string $last_name = null,
        readonly ?string $username = null,
        readonly ?string $language_code = null,
        readonly ?bool $is_premium = null,
        readonly bool $is_bot = false,
    ) {}

    static function fromNutgram(\SergiX44\Nutgram\Telegram\Types\User\User $user): self
    {
        return self::from([
            ...$user->toArray(),
            'id' => null,
            'tg_id' => $user->id,
        ]);
    }
}
