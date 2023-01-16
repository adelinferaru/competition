<?php

namespace App\Interfaces;

use App\Models\Competition;
use App\Models\Player;

interface GenericStorageEngineInterface
{
    public function setCompetition(Competition $competition): GenericStorageEngineInterface;

    public function getCompetitionByStorageKey(string $key): array;

    public function addCompetitionDataToStorage ($key, $value):int;

    public function getCompetitionInfo (string $key, string $value):string|bool;

    public function getCompetitionPlayers(string $key, int $start, int $end, bool $withScores = true, string $orderDir = 'desc'):array|bool;

    public function getCompetitionPlayersCount(string $key):int;

    public function setPlayer(Player $player): GenericStorageEngineInterface;

    public function addPlayerDataToStorage ($key, $value):int;

    public function getPlayerByStorageKey(string $key): array;

    public function setKey(string $key): GenericStorageEngineInterface;

    public function setScore(int $score): void;

    public function getScore(string $key, string $value): int|bool;

    public function getCompetitionLeaderboard(array $options = []): array;

    public function getPlayerRank(string $playerId): int;
}
