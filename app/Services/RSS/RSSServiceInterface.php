<?php

declare(strict_types=1);

namespace App\Services\RSS;

use App\Data\RSS\RSSData;
use Lowel\Rss\RssException;

interface RSSServiceInterface
{
    /**
     * @throws RssException
     */
    public function getRSSFeed(): RSSData;
}
