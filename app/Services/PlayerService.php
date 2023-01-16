<?php

namespace App\Services;

use App\Interfaces\GenericPlayerInterface;

class PlayerService
{
    protected GenericPlayerInterface $playerContract;

    public function __construct(GenericPlayerInterface $playerContract)
    {
        $this->playerContract = $playerContract;
    }

    public function createPlayer(string $playerUserName):mixed
    {
        return $this->playerContract->createPlayer($playerUserName);
    }
}
