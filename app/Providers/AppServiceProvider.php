<?php

namespace App\Providers;

use App\Channel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
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

        if(App::environment('local')) {
            App::register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        \view()->composer('*', function($view) {
            $channels = \cache()->rememberForever('channels', function () {
                return Channel::all();
            });
            $view->with('channels', $channels);
        });
    }
}
