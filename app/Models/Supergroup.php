<?php

namespace App\Models;

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
}
