<?php

namespace App\Exceptions\Repositories\Telegram\Chat;

use App\Data\Telegram\Chat\Types\SupergroupData;
use App\Exceptions\CheckedException;
use Exception;
use Throwable;

class SupergroupNotFoundException extends CheckedException
{
    protected string $messageFormat = "Supergroup '%s' was not founded";

    public function __construct(SupergroupData $supergroupData)
    {
        parent::__construct($this->formatMessage($supergroupData->title));
    }
}
