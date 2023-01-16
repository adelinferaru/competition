<?php

namespace App\Repositories;

use App\Exceptions\CompetitionEnrollPlayerException;
use App\Exceptions\CompetitionIncreaseScoreException;
use App\Exceptions\GenericApiError;
use App\Exceptions\CompetitionNotFoundException;
use App\Models\Competition;
use App\Models\Player;

class CompetitionRepository
{
    protected Competition $competition;

    /**
     * @param Competition $competition
     */
    public function __construct(Competition $competition)
    {
        $this->competition = $competition;
    }

    public function createNewCompetition(array $data) {
        $competitionData = collect($data)->only(['name', 'max_players'])->toArray();
        return $this->competition::create($competitionData);
    }

    public function getLeaderboard(string $competitionId, $options = []): \Illuminate\Database\Eloquent\Collection|array
    {
        /* @var $competition Competition */
        $competition = $this->competition::where('id', $competitionId)->first();
        if (count($options) && isset($options['orderByScore'])) {
            $orderByScore = $options['orderByScore'];
            if (in_array(strtolower($orderByScore), ['asc', 'desc'])) {
                $competition->orderByScore = $orderByScore;
            }
        }
        return $competition->append('players')->only(['name', 'players']);
    }

    /**
     * @throws CompetitionEnrollPlayerException
     */
    public function enrollPlayer(string $competitionId, string $playerId): array
    {
        try {
            $competition = $this->competition::where(['id' => $competitionId])->first();
            $player = Player::where('id', $playerId)->first();
            if ($competition && $player) {
                $competition->enrolled_players()->syncWithoutDetaching($playerId, ['score' => 0]);
            }
            else {
                throw (new CompetitionEnrollPlayerException('Invalid competition or player data.', 500));
            }
            return ['competition' => $competition, 'player' => $player, 'score' => 0];
        } catch (\Throwable $e) {
            report($e);
            throw (new CompetitionEnrollPlayerException('Invalid competition or player data.', 500));
        }
    }

    /**
     * @throws CompetitionIncreaseScoreException
     */
    public function increaseScore(string $competitionId, string $playerId)
    {
        try {
            $competition = $this->competition::where(['id' => $competitionId])->first();
            $player = $competition->enrolled_players->find($playerId);
            if ($competition && $player) {
                $newScore = $player->pivot->score + 1;
                $competition->enrolled_players()->updateExistingPivot($playerId, ['score' => $newScore]);
                return ['competition' => $competition, 'player' => $player, 'score' => $newScore];
            }
            else {
                throw (new CompetitionIncreaseScoreException('Invalid competition or player data.', 500));
            }
        } catch (\Throwable $e) {
            report($e);
            throw (new CompetitionIncreaseScoreException('Invalid competition or player data.', 500));
        }
    }
}
