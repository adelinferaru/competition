<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface GenericCompetitionInterface
{
    public function createCompetition(string $competitionName, int $maxNumberOfPlayers, array $options = []);

    public function getCompetitionByStorageKey($key): array;

    public function getPlayerByStorageKey($key): array;

    public function playerCanEnterCompetition($competitionId, &$error = null):bool;

    public function playerAlreadyEnrolled($competitionId, $playerId):bool;

    public function enrollPlayer(string $competitionId, string $playerId, array $options = []);

    public function increaseScore(string $competitionId, string $playerId, array $options = []):int;

    public function getLeaderboard(string $competitionId, array $options = []);

    public static function getLeaderBoardStorageKey($competitionId):string;
}
