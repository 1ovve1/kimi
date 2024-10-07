<?php

namespace App\Models;

use App\Data\Telegram\Chat\Types\ChannelData;
use App\Exceptions\Repositories\Telegram\Chat\ChannelNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'tg_id',
        'title',
    ];

    /**
     * @return MorphOne<Chat>
     */
    public function chat(): MorphOne
    {
        return $this->morphOne(Chat::class, 'target');
    }

    /**
     * @throws ChannelNotFoundException
     */
    public static function findForChannelData(ChannelData $channelData): Channel
    {
        return Channel::whereId($channelData->id)
            ->orWhere('tg_id', $channelData->tg_id)
            ->first() ?? throw new ChannelNotFoundException($channelData);
    }
}
