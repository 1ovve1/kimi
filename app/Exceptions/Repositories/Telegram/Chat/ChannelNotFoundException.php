<?php

namespace App\Exceptions\Repositories\Telegram\Chat;

use App\Data\Telegram\Chat\Types\ChannelData;
use App\Exceptions\CheckedException;

class ChannelNotFoundException extends CheckedException
{
    protected string $messageFormat = "Channel '%s' was not founded";

    public function __construct(ChannelData $channelData)
    {
        parent::__construct($this->formatMessage($channelData->title));
    }
}
