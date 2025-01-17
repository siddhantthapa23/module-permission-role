<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Using class based composers...
         */
        View::composer(
            'backend.sidebar',
            'App\Http\ViewComposers\Backend\SidebarComposer'
        );
        View::composer(
            'backend.breadcrumb',
            'App\Http\ViewComposers\Backend\BreadCrumbComposer'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}