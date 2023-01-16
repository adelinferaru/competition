<?php

namespace App\GreenHorse;

use App\Interfaces\GenericStorageEngineInterface;
use App\Models\Competition;
use App\Models\Player;
use Illuminate\Redis\Connections\Connection as RedisConnection;
use Illuminate\Support\Facades\Redis;

class RedisStorageEngine implements \App\Interfaces\GenericStorageEngineInterface
{
    private mixed $redis;
    private Competition $competition;
    private Player $player;
    private string $setKey;

    public function __construct() {
        $this->redis = Redis::connection()->client();
    }

    public function setKey(string $key): GenericStorageEngineInterface
    {
        $this->setKey = $key;
        return $this;
    }

    public function setCompetition(Competition $competition): GenericStorageEngineInterface
    {
        $this->competition = $competition;
        return $this;
    }

    public function getCompetitionByStorageKey(string $key): array
    {
        return $this->redis->hGetAll($key);
    }

    public function addCompetitionDataToStorage ($key, $value):int
    {
        return $this->redis->hMSet($key, $value);
    }

    public function getCompetitionInfo (string $key, string $value):string|bool
    {
        return $this->redis->hGet($key, $value);
    }

    public function getCompetitionPlayers(string $key, int $start, int $end, bool $withScores = true, string $orderDir = 'desc'):array|bool
    {
        $command = $orderDir == 'desc' ? 'zRevRange' : 'zRange';
        return $this->redis->{$command}($key, $start, $end, ['withscores' => $withScores]);
    }

    public function getCompetitionPlayersCount(string $key):int
    {
        return $this->redis->zCard($key);
    }

    public function setPlayer(Player $player): GenericStorageEngineInterface
    {
        $this->player = $player;
        return $this;
    }

    public function addPlayerDataToStorage ($key, $value):int
    {
        return $this->redis->hMSet($key, $value);
    }

    public function getPlayerByStorageKey(string $key): array
    {
        return $this->redis->hGetAll($key);
    }

    public function setScore(int $score): void
    {
        $this->redis->zAdd($this->setKey, $score, Player::getStorageKeyById($this->player->id));
    }

    public function getScore(string $key, string $value): int|bool
    {
        return $this->redis->zScore($key, $value);
    }

    public function getCompetitionLeaderboard(array $options = []): array
    {
        return $this->redis->zRevRange($this->setKey, 0, -1, ['WITHSCORES' => (bool)$options['withScores']]);
    }

    public function getPlayerRank(string $playerId): int
    {
        return $this->redis->zRank($this->setKey, $this->player->id);
    }
}
