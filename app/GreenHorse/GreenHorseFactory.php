<?php

namespace App\GreenHorse;

use App\Exceptions\NoStorageImplementationException;
use App\Interfaces\GenericStorageEngineInterface;
use App\Models\Competition;
use App\Models\Player;

class GreenHorseFactory
{

    /**
     * Create a Leaderboard storage for fast access
     *
     * @throws \Exception
     */
    public static function makeGreenHorse(Competition $competition, Player $player, string $setkey): GreenHorse
    {
        return new GreenHorse(self::getStorageEngine(), $competition, $player, $setkey);
    }

    /**
     * @throws NoStorageImplementationException
     */
    public static function getStorageEngine() :GenericStorageEngineInterface
    {
        $storageEngine = config('greenhorse.leaderboard_storage');
        if ($storageEngine == 'redis') {
            return new RedisStorageEngine();
        } else {
            throw new NoStorageImplementationException('No storage engine implementation.');
        }
    }
}
