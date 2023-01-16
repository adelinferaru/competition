<?php

namespace App\Repositories;

use App\Models\Player;

class PlayerRepository
{
    protected Player $player;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function createPlayer(array $playerData) {
        return $this->player::create($playerData);
    }
}
