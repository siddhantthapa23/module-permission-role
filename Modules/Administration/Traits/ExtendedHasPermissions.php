<?php

namespace Modules\Administration\Traits;

use Modules\Administration\Registrars\PermissionRegistrar;
use Illuminate\Database\Eloquent\Collection;

trait ExtendedHasPermissions
{
    /**
     * Lookup all defined permissions and filter down to just those matching the specified id.
     * 
     * @param string|int $permissionId
     * @return Collection
     */
    public function getMatchPermissions($permissionId): Collection
    {
        return app(PermissionRegistrar::class)->getPermissions()
                ->filter(function ($permission) use ($permissionId) {
                    return $permission->id == $permissionId;
                });
    }
}