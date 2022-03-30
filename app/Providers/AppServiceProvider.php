<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Repository\BaseRepository;

use App\Services\Contracts\BaseServiceInterface;
use Service\BaseService;

use App\Repositories\Contracts\SpeechRepositoryInterface;
use Repository\SpeechRepository;

use App\Services\Contracts\SpeechServiceInterface;
use Service\SpeechService;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(BaseServiceInterface::class, BaseService::class);
        // Speech
        $this->app->bind(SpeechRepositoryInterface::class, SpeechRepository::class);
        $this->app->bind(SpeechServiceInterface::class, SpeechService::class);
        // end Speech
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
