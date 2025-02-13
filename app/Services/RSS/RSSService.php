<?php

declare(strict_types=1);

namespace App\Services\RSS;

use App\Data\RSS\RSSData;
use App\Data\RSS\RSSItemData;
use App\Services\Abstract\AbstractService;
use Lowel\Rss\Factory as RSSFactory;

class RSSService extends AbstractService implements RSSServiceInterface
{
    public function getRSSFeed(): RSSData
    {
        $client = (new RSSFactory)->fromUrl(config('rss.url'));

        $items = [];
        foreach ($client->getAttribute('item') as $item) {
            $items[] = new RSSItemData(
                $item->getAttribute('title')->toString(),
                $item->getAttribute('description')->toString(),
            );
        }

        return new RSSData(
            $client->getAttribute('title')->toString(),
            $items,
        );
    }
}
