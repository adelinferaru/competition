<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlayerCreateRequest;
use App\Services\PlayerService;
use Illuminate\Http\JsonResponse;

class PlayerController extends Controller
{
    /**
     * The Player service instance.
     */
    protected PlayerService $playerService;

    /**
     * Create a new controller instance.
     *
     * @param PlayerService $playerService
     * @return void
     */
    public function __construct(PlayerService $playerService) {
        $this->playerService = $playerService;
    }

    public function store(PlayerCreateRequest $request): JsonResponse
    {
        $result = ['status' => 200];
        try {
            $playerUserName = $request->validated('user_name');
            $player = $this->playerService->createPlayer($playerUserName);
            $result['player_id'] = $player->id;
        } catch (\Throwable $e) {
            report($e);
            $result['status'] = $e->getCode() ?? 500;
            $result['error'] = $e->getMessage();
        }
        return response()->json($result, $result['status']);
    }
}
