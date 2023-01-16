<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompetitionCreateRequest;
use App\Http\Requests\LeaderBoardRequest;
use App\Services\CompetitionService;
use http\Env\Request;
use Illuminate\Http\JsonResponse;

class CompetitionController extends Controller
{
    /**
     * The Competition service instance.
     */
    protected CompetitionService $competitionService;

    /**
     * Create a new controller instance.
     *
     * @param CompetitionService $competitionService
     * @return void
     */
    public function __construct(CompetitionService $competitionService) {
        $this->competitionService = $competitionService;
    }

    /**
     * @param CompetitionCreateRequest $request
     * @return JsonResponse
     */
    public function store(CompetitionCreateRequest $request): JsonResponse
    {
        $result = ['status' => 200];
        try {
            $competition = $this->competitionService->createNewCompetition($request->validated());
            $result['competition_id'] = $competition->id;
        } catch (\Throwable $e) {
            report($e);
            $result['status'] = $e->getCode();
            $result['error'] = $e->getMessage();
        }
        return response()->json($result, $result['status']);
    }

    public function getLeaderboard (string $competitionId, LeaderBoardRequest $leaderBoardRequest): JsonResponse
    {
        $result = ['status' => 200];
        try {
            $leaderBoard = $this->competitionService->getLeaderboard($competitionId, $leaderBoardRequest->validated());
            $result['leaderboard'] = $leaderBoard;
        } catch (\Throwable $e) {
            report($e);
            $result['status'] = $e->getCode();
            $result['error'] = $e->getMessage();
        }
        return response()->json($result, $result['status']);
    }

    public function enrollPlayer(string $competitionId, string $playerId): JsonResponse
    {
        $result = ['status' => 200];
        try {
            $this->competitionService->enrollPlayer($competitionId, $playerId);
        } catch (\Throwable $e) {
            report($e);
            $result['status'] = $e->getCode();
            $result['error'] = $e->getMessage();
        }
        return response()->json($result, $result['status']);
    }

    public function increaseScore(string $competitionId, string $playerId): JsonResponse
    {
        $result = ['status' => 200];
        try {
            $result['score'] = $this->competitionService->increaseScore($competitionId, $playerId);
        } catch (\Throwable $e) {
            report($e);
            $result['status'] = $e->getCode();
            $result['error'] = $e->getMessage();
        }
        return response()->json($result, $result['status']);
    }
}
