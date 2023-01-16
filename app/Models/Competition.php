<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;

class Competition extends AbstractGreenHorseModel
{
    use HasFactory, HasUuids;

    protected $table = 'competitions';
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'max_players'
    ];

    public $orderByScore = 'desc';

    // protected $appends = ['players'];

    public function enrolled_players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'competition_player', 'competition_id', 'player_id')
            ->withPivot(['score'])->withTimestamps();
    }

    public function getPlayersAttribute (): \Illuminate\Support\Collection
    {
        $allPlayers = $this->enrolled_players()->orderBy('competition_player.score', $this->orderByScore)->get();
        return collect($allPlayers->map(function ($player) {
            return ['id' => $player->id, 'user_name' => $player->user_name, 'score' => $player->pivot->score];
        }));
    }
}
