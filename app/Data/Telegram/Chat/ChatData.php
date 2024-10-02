<?php

namespace App\Data\Telegram\Chat;

use App\Data\Telegram\Chat\Types\ChannelData;
use App\Data\Telegram\Chat\Types\GroupData;
use App\Data\Telegram\Chat\Types\PrivateData;
use App\Data\Telegram\Chat\Types\SupergroupData;
use App\Models\Channel;
use App\Models\Group;
use App\Models\Supergroup;
use App\Models\User;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class ChatData extends Data
{
    #[Computed]
    public readonly null|PrivateData|ChannelData|GroupData|SupergroupData $target;

    public function __construct(
        readonly int $id,
        User|Group|Supergroup|Channel $target,
        readonly bool $interactive_mode = false,
    ) {
        if ($target instanceof User) {
            $this->target = PrivateData::from($target);
        } elseif ($target instanceof Group) {
            $this->target = GroupData::from($target);
        } elseif ($target instanceof Supergroup) {
            $this->target = SupergroupData::from($target);
        } elseif ($target instanceof Channel) {
            $this->target = ChannelData::from($target);
        }
    }
}
