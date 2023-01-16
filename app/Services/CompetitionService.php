<?php

namespace App\Services;

use App\Interfaces\GenericCompetitionInterface;
use Illuminate\Database\Eloquent\Collection;

class CompetitionService
{
    protected GenericCompetitionInterface $competitionContract;

    public function __construct(GenericCompetitionInterface $competition)
    {
        $this->competitionContract = $competition;
    }

    public function createNewCompetition ($data) {
        return $this->competitionContract->createCompetition($data['name'], $data['max_players']);
    }

    public function getLeaderboard (string $competitionId, array $options = []) {

        return $this->competitionContract->getLeaderboard($competitionId, $options);
    }

    public function enrollPlayer(string $competitionId, string $playerId) {
        return $this->competitionContract->enrollPlayer($competitionId, $playerId);
    }

    public function increaseScore(string $competitionId, string $playerId):int
    {
        return $this->competitionContract->increaseScore($competitionId, $playerId);
    }
}
