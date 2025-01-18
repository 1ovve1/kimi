<?php

namespace App\Models;

use App\Data\Telegram\Chat\Types\SupergroupData;
use App\Exceptions\Repositories\Telegram\Chat\SupergroupNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Supergroup extends Model
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
     * @throws SupergroupNotFoundException
     */
    public static function findForSupergroupData(SupergroupData $supergroupData): Supergroup
    {
        return Supergroup::whereId($supergroupData->id)
            ->orWhere('tg_id', $supergroupData->tg_id)
            ->first() ?? throw new SupergroupNotFoundException($supergroupData);
    }
}
