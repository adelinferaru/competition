<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface GenericPlayerInterface
{
    public function createPlayer(string $userName, array $options = []);

    public function playCompetition(string $competitionId, string $playerId, array $options = []);

    public function getScore(string $playerId);
}
