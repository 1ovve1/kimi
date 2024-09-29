<?php

namespace App\Data\Telegram\Chat\Types;

use SergiX44\Nutgram\Telegram\Properties\ChatType;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class PrivateData extends Data
{
    #[Computed]
    public readonly ChatType $type;

    public function __construct(
        readonly int $id,
        readonly string $first_name,
        readonly ?string $last_name = null,
        readonly ?string $username = null,
        readonly ?string $language_code = null,
        readonly ?bool $is_premium = null,
        readonly bool $is_bot = false,
    ) {
        $this->type = ChatType::PRIVATE;
    }
}
