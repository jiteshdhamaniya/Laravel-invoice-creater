<?php

namespace App\Providers;
use PDF;

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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
    }
}
