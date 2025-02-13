<?php

namespace App\Data\RSS;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class RSSData extends Data
{
    public function __construct(
        readonly string $title,
        #[DataCollectionOf(RSSItemData::class)]
        readonly array $items,
    ) {}
}
