<?php

use App\Http\Controllers\Api\V1\CompetitionController;
use App\Http\Controllers\Api\V1\PlayerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::prefix('competition-management')->middleware('throttle:60:1')->group(function () {
    Route::post('/competitions', [CompetitionController::class, 'store'])->name('competition.store');
    Route::get('/competitions/{competitionId}', [CompetitionController::class, 'getLeaderboard'])->name('competition.leaderboard');
    Route::post('/competitions/{competitionId}/enroll-player/{playerId}', [CompetitionController::class, 'enrollPlayer'])->name('competition.enrollPlayer');
    Route::post('/competitions/{competitionId}/increase-score/{playerId}', [CompetitionController::class, 'increaseScore'])->name('competition.enroll-player');
});

Route::prefix('player-management')->middleware('throttle:60:1')->group(function () {
    Route::post('/players', [PlayerController::class, 'store'])->name('player.store');

});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found.'], 404);
});
