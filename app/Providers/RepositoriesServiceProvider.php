<?php

namespace App\Providers;

use App\Models\Repositories\Publico\Area\AreaInterface;
use App\Models\Repositories\Publico\Area\AreaRepository;
use App\Models\Repositories\Publico\Competitions\CompetitionsInterface;
use App\Models\Repositories\Publico\Competitions\CompetitionsRepository;
use App\Models\Repositories\Publico\CurrentSeason\CurrentSeasonInterface;
use App\Models\Repositories\Publico\CurrentSeason\CurrentSeasonRepository;
use App\Models\Repositories\Publico\WinnerSeason\WinnerSeasonInterface;
use App\Models\Repositories\Publico\WinnerSeason\WinnerSeasonRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(AreaInterface::class, AreaRepository::class);
        $this->app->bind(CurrentSeasonInterface::class, CurrentSeasonRepository::class);
        $this->app->bind(WinnerSeasonInterface::class, WinnerSeasonRepository::class);
        $this->app->bind(CompetitionsInterface::class, CompetitionsRepository::class);
    }

}
