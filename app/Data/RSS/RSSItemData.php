<?php

namespace App\Data\RSS;

use DateTimeInterface;
use Spatie\LaravelData\Data;

class RSSItemData extends Data
{
    public function __construct(
        readonly string $title,
        readonly string $description,
        readonly string $pubDate,
    ) {}

    public function fullText(): string
    {
        return sprintf("%s\n\n%s", $this->title, $this->description);
    }
}
