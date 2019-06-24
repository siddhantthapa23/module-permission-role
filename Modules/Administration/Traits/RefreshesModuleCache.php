<?php

namespace Modules\Administration\Traits;

use Modules\Administration\Registrars\PermissionRegistrar;

trait RefreshesModuleCache 
{
    public static function bootRefreshesModuleCache()
    {
        static::saved(function() {
            app(PermissionRegistrar::class)->forgetCachedModules();
        });

        static::deleted(function() {
            app(PermissionRegistrar::class)->forgetCachedModules();
        });
    }
}