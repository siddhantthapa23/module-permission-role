<?php

namespace Modules\Administration\Providers;

use Spatie\Permission\PermissionServiceProvider as SpatiePermissionServiceProvider;
use Modules\Administration\Registrars\PermissionRegistrar;

class PermissionServiceProvider extends SpatiePermissionServiceProvider
{
    public function boot(PermissionRegistrar $permissionLoader)
    {
        $permissionLoader->registerPermissions();

        $this->app->singleton(PermissionRegistrar::class, function($app) use ($permissionLoader) {
            return $permissionLoader;
        });
    }
}