<?php

namespace App\Providers;

use App\GreenHorse\GreenHorseCompetition;
use App\GreenHorse\GreenHorsePlayer;
use App\Interfaces\GenericCompetitionInterface;
use App\Interfaces\GenericPlayerInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind default contract for Generic Competition
        $this->app->bind(GenericCompetitionInterface::class, GreenHorseCompetition::class);

        // Bind default contract for Generic Player
        $this->app->bind(GenericPlayerInterface::class, GreenHorsePlayer::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
