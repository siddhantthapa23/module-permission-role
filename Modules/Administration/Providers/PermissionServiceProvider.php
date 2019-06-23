<?php

namespace Modules\Administration\Providers;

use Illuminate\Filesystem\Filesystem;
use Modules\Administration\Registrars\PermissionRegistrar;
use Spatie\Permission\PermissionServiceProvider as SpatiePermissionServiceProvider;

class PermissionServiceProvider extends SpatiePermissionServiceProvider
{
    /**
     * Boot the application events.
     * 
     * @return void
     */
    public function boot(PermissionRegistrar $permissionLoader, Filesystem $filesystem)
    {
        parent::boot($permissionLoader, $filesystem);
    }

    /**
     * Register the service provider.
     * 
     * @return void
     */
    public function register()
    {
        parent::register();
    }
}