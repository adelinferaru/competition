<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Player extends AbstractGreenHorseModel
{
    use HasFactory, HasUuids;

    protected $table = 'players';
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'user_name',
    ];


    public function competitions(): BelongsToMany
    {
        return $this->belongsToMany(Competition::class, 'competition_player', 'player_id', 'competition_id')
            ->withPivot(['score'])->withTimestamps();
    }
}
