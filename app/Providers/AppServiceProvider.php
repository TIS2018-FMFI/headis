<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        view()->composer('*', function ($view) {
            $user = null;
            if (auth()->check()) {
                $user = User::find(auth()->user()->id);
            }
            $view->with('currentAuthUser', $user);
        });
        if (App::environment() == 'production') {
            URL::forceRootUrl(Config::get('app.url'));
            if (str_contains(Config::get('app.url'), 'https://')) {
                URL::forceScheme('https');
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
