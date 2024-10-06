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
use SergiX44\Nutgram\Telegram\Properties\ChatType;
use SergiX44\Nutgram\Telegram\Types\Chat\Chat;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class ChatData extends Data
{
    #[Computed]
    public readonly null|PrivateData|ChannelData|GroupData|SupergroupData $target;

    public function __construct(
        readonly ?int $id,
        mixed $target,
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
        } else {
            $this->target = $target;
        }
    }

    static function fromNutgram(Chat $chat): self
    {
        $target = match ($chat->type) {
            ChatType::PRIVATE => PrivateData::fromNutgram($chat),
            ChatType::GROUP => GroupData::fromNutgram($chat),
            ChatType::SUPERGROUP => SupergroupData::fromNutgram($chat),
            ChatType::CHANNEL => ChannelData::fromNutgram($chat),
            ChatType::SENDER => throw new \RuntimeException('To be implemented'),
            default => null,
        };

        return self::from([
            ...$chat->toArray(),
            'id' => null,
            'target' => $target,
        ]);
    }
}
