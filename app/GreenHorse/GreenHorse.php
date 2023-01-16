<?php

namespace App\GreenHorse;

use App\Interfaces\GenericStorageEngineInterface;
use App\Models\Competition;
use App\Models\Player;

class GreenHorse
{
    private GenericStorageEngineInterface $storageEngine;

    public function __construct(
        GenericStorageEngineInterface $storageEngine,
        Competition $competition = null,
        Player $player = null ,
        string $setKey = null
    ) {
        $this->storageEngine = $storageEngine;
        if ($competition) {
            $this->storageEngine->setCompetition($competition);
        }

        if ($player) {
            $this->storageEngine->setPlayer($player);
        }

        if ($setKey) {
            $this->storageEngine->setKey($setKey);
        }
    }

    public function getStorageEngine(): GenericStorageEngineInterface
    {
        return $this->storageEngine;
    }

    public function setPlayerScore (int $score):void
    {
        $this->storageEngine->setScore($score);
    }

    /*public function getStorageKey($key) :string
    {
        return config('greenhorse.keys.' . $key, $key);
    }

    public function getCompetitionLeaderBoardKey($competitionId): string
    {
        $storageKey = $this->getStorageKey('leaderboard');
        return str_ireplace('{id}', $competitionId, $storageKey);
    }*/
}
