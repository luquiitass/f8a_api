<?php

namespace App\Providers;

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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            //$this->app->register('Iber\Generator\ModelGeneratorProvider');
        }

//        $this->app->bind('path.public', function() {
//            return 'lucasdb1.esy.es/public_html/';
//        });
    }
}
