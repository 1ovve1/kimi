<?php

namespace App\Models;

use App\Models\Abstract\Types\ChatInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Group extends Model implements ChatInterface
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
    ];

    public $incrementing = false;

    /**
     * @return MorphOne<Chat>
     */
    public function chat(): MorphOne
    {
        return $this->morphOne(Chat::class, 'target');
    }

    /**
     * Group chat
     */
    public function getChatId(): int
    {
        return $this->id;
    }
}
