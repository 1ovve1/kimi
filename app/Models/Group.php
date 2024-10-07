<?php

namespace App\Models;

use App\Data\Telegram\Chat\Types\GroupData;
use App\Exceptions\Repositories\Telegram\Chat\GroupNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Group extends Model
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
     * @throws GroupNotFoundException
     */
    public static function findForGroupData(GroupData $groupData): Group
    {
        return Group::whereId($groupData->id)
            ->orWhere('tg_id', $groupData->tg_id)
            ->first() ?? throw new GroupNotFoundException($groupData);
    }
}
