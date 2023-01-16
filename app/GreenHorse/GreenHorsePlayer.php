<?php

namespace App\GreenHorse;

use App\Exceptions\NoStorageImplementationException;
use App\Interfaces\GenericStorageEngineInterface;
use App\Models\AbstractGreenHorseModel;
use App\Models\Competition;
use App\Models\Player;
use App\Repositories\PlayerRepository;
use Illuminate\Support\Facades\Redis;

class GreenHorsePlayer implements \App\Interfaces\GenericPlayerInterface
{
    private PlayerRepository $playerRepository;
    protected GenericStorageEngineInterface $storageEngine;

    /**
     * @throws NoStorageImplementationException
     */
    public function __construct(PlayerRepository $playerRepository) {
        $this->playerRepository = $playerRepository;
        $this->storageEngine = GreenHorseFactory::getStorageEngine();
    }

    public function createPlayer(string $userName, array $options = [])
    {
        $player =  $this->playerRepository->createPlayer([
            'user_name' => $userName,
        ]);

        if ($player instanceof AbstractGreenHorseModel) {
            $storageKey = Player::getStorageKeyById($player->id);
            $playerData = [
                'user_name' => $player->user_name
            ];
            $this->storageEngine->addPlayerDataToStorage($storageKey, $playerData);
        }

        return $player;
    }

    public function playCompetition(string $competitionId, string $playerId, array $options = [])
    {
        // TODO: Implement playCompetition() method.
    }

    public function getScore(string $playerId)
    {
        // TODO: Implement getScore() method.
    }
}
