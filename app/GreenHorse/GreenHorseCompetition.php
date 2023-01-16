<?php

namespace App\GreenHorse;

use App\Interfaces\GenericCompetitionInterface;
use App\Interfaces\GenericStorageEngineInterface;
use App\Models\AbstractGreenHorseModel;
use App\Models\Competition;
use App\Models\Player;
use App\Repositories\CompetitionRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class GreenHorseCompetition implements GenericCompetitionInterface
{
    protected CompetitionRepository $competitionRepository;
    protected GenericStorageEngineInterface $storageEngine;

    /**
     * @throws \App\Exceptions\NoStorageImplementationException
     */
    public function __construct(CompetitionRepository $competitionRepository) {
        $this->competitionRepository = $competitionRepository;
        $this->storageEngine = GreenHorseFactory::getStorageEngine();
    }

    public function createCompetition(string $competitionName, int $maxNumberOfPlayers, array $options = []): Competition|AbstractGreenHorseModel
    {
        $data = [
          'name' => $competitionName,
          'max_players' => $maxNumberOfPlayers
        ];

        $competition = $this->competitionRepository->createNewCompetition($data);
        if ($competition instanceof AbstractGreenHorseModel) {
            /** @var $competition Competition */
            $storageKey = Competition::getStorageKeyById($competition->id);
            $competitionData = [
                'name' => $competition->name,
                'max_players' => $competition->max_players
            ];
            $this->storageEngine->addCompetitionDataToStorage($storageKey, $competitionData);
        }

        return $competition;
    }

    public function playerCanEnterCompetition($competitionId, &$error = null):bool {
        $leaderboardKey = self::getLeaderBoardStorageKey($competitionId);
        $currentNumberOfPlayers = $this->storageEngine->getCompetitionPlayersCount($leaderboardKey);

        $competitionStorageKey = Competition::getStorageKeyById($competitionId);
        $maxPlayers = $this->storageEngine->getCompetitionInfo($competitionStorageKey, 'max_players');

        if ($currentNumberOfPlayers == $maxPlayers) {
            $error = 'The Competition is full!';
            return false;
        }
        return true;
    }

    public function playerAlreadyEnrolled($competitionId, $playerId):bool
    {
        $leaderboardKey = self::getLeaderBoardStorageKey($competitionId);
        $playerKey = Player::getStorageKeyById($playerId);

        $score = $this->storageEngine->getScore($leaderboardKey, $playerKey);

        return $score !== false;
    }

    /**
     * @throws \App\Exceptions\CompetitionEnrollPlayerException
     * @throws \Exception
     */
    public function enrollPlayer(string $competitionId, string $playerId, array $options = [])
    {
        $error = null;
        $errorCode = 400;
        if ($this->playerCanEnterCompetition($competitionId, $error)) {
            if (!$this->playerAlreadyEnrolled($competitionId, $playerId)) {
                $data = $this->competitionRepository->enrollPlayer($competitionId, $playerId);

                if ($data) {
                    list(
                        'competition' => $competition,
                        'player' => $player,
                        'score' => $score
                        ) = $data;
                    /* @var $competition Competition */
                    $leaderBoardKey = self::getLeaderBoardStorageKey($competition->id);
                    $greenHorse = GreenHorseFactory::makeGreenHorse($competition, $player, $leaderBoardKey);
                    $greenHorse->setPlayerScore($score);
                }
            }
            else {
                $error ='Player already enrolled in this competition!';
                $errorCode = 403;
            }
        }
        if($error) {
            throw new \Exception($error, $errorCode);
        }
    }

    public static function getLeaderBoardStorageKey($competitionId):string {
        return str_ireplace('{id}', $competitionId, config('greenhorse.keys.leaderboard'));
    }

    /**
     * @throws \App\Exceptions\CompetitionIncreaseScoreException
     * @throws \Exception
     */
    public function increaseScore(string $competitionId, string $playerId, array $options = []):int
    {
        $data = $this->competitionRepository->increaseScore($competitionId, $playerId);
        if ($data) {
            list(
                'competition' => $competition,
                'player' => $player,
                'score' => $score
                ) = $data;

            // dd($data);
            /* @var $competition Competition */
            $storageKey = self::getLeaderBoardStorageKey($competitionId);
            $greenHorse = GreenHorseFactory::makeGreenHorse($competition, $player, $storageKey);
            $greenHorse->setPlayerScore($score);

            return $score;
        }

        return false;
    }

    public function getLeaderboard(string $competitionId, array $options = []): \Illuminate\Database\Eloquent\Collection|array
    {
        $response = [];
        $orderedPlayers = [];
        $storageKey = self::getLeaderBoardStorageKey($competitionId);
        $order = $options['orderByScore'] ?? 'desc';
        $playerKeys = $this->storageEngine->getCompetitionPlayers($storageKey, 0, -1, true, $order);
        // dd($playerKeys);
        if($playerKeys) {
            foreach ($playerKeys as $playerKey => $score) {
                $playerData = $this->getPlayerByStorageKey($playerKey);
                $orderedPlayers [] = [
                    'user_name' => $playerData['user_name'],
                    'score' => $score
                ];
            }
            $response = [
                'competition' => $this->getCompetitionByStorageKey(Competition::getStorageKeyById($competitionId)),
                'players' => $orderedPlayers
            ];
        }


        return $response;
    }

    public function getPlayerByStorageKey($key): array
    {
        return $this->storageEngine->getPlayerByStorageKey($key);
    }

    public function getCompetitionByStorageKey($key): array
    {
        return $this->storageEngine->getCompetitionByStorageKey($key);
    }
}

