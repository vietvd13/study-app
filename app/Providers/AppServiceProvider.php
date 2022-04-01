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

use App\Repositories\Contracts\ClassRepositoryInterface;
use Repository\classRepository;

use Service\ClassService;
use App\Services\Contracts\ClassServiceInterface;

use App\Repositories\Contracts\TestRepositoryInterface;
use Repository\TestRepository;

use App\Repositories\Contracts\CourseRepositoryInterface;
use Repository\CourseRepository;

use Service\CourseService;
use App\Services\Contracts\CourseServiceInterface;
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

        // class
        $this->app->bind(ClassRepositoryInterface::class, classRepository::class);
        $this->app->bind(ClassServiceInterface::class, ClassService::class);
        // end class

        // test
        $this->app->bind(TestRepositoryInterface::class, TestRepository::class);
        $this->app->bind(TestServiceInterface::class, TestService::class);
        // end test

        // course
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(CourseServiceInterface::class, CourseService::class);
        // end course
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
