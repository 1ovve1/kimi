<?php

namespace App\Exceptions\Repositories\Telegram\Chat;

use App\Data\Telegram\Chat\Types\GroupData;
use App\Exceptions\CheckedException;

class GroupNotFoundException extends CheckedException
{
    protected string $messageFormat = "Group '%s' was not founded";

    public function __construct(GroupData $groupData)
    {
        parent::__construct($this->formatMessage($groupData->title));
    }
}
