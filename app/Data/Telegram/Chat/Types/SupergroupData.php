<?php

namespace App\Data\Telegram\Chat\Types;

use SergiX44\Nutgram\Telegram\Properties\ChatType;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class SupergroupData extends Data
{
    #[Computed]
    public readonly ChatType $type;

    public function __construct(
        readonly int $id,
        readonly string $title,
    ) {
        $this->type = ChatType::SUPERGROUP;
    }
}
