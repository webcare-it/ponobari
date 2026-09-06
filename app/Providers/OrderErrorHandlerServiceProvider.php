<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class OrderErrorHandlerServiceProvider extends ServiceProvider
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
        // Share error handling logic with all admin order views
        View::composer(['admin.customer.*', 'admin.user.order_list'], function ($view) {
            // Check if we're on an admin order page
            $currentRoute = Route::currentRouteName();
            
            // Add error bypass logic to views
            $view->with('orderErrorBypass', true);
        });
    }
}