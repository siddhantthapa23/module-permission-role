<?php

namespace Modules\Administration\Traits;

use Spatie\Permission\Traits\HasRoles as SpatieHasRoles;

trait HasRoles
{
    /** manual method overriding */
    use SpatieHasRoles, HasPermissions {
        SpatieHasRoles:: bootHasPermissions insteadOf HasPermissions;
        SpatieHasRoles:: getPermissionClass insteadOf HasPermissions;
        SpatieHasRoles:: permissions insteadOf HasPermissions;
        SpatieHasRoles:: scopePermission insteadOf HasPermissions;
        SpatieHasRoles:: convertToPermissionModels insteadOf HasPermissions;
        SpatieHasRoles:: hasPermissionTo insteadOf HasPermissions;
        SpatieHasRoles:: hasUncachedPermissionTo insteadOf HasPermissions;
        SpatieHasRoles:: checkPermissionTo insteadOf HasPermissions;
        SpatieHasRoles:: hasAnyPermission insteadOf HasPermissions;
        SpatieHasRoles:: hasAllPermissions insteadOf HasPermissions;
        SpatieHasRoles:: hasPermissionViaRole insteadOf HasPermissions;
        SpatieHasRoles:: hasDirectPermission insteadOf HasPermissions;

        SpatieHasRoles:: getPermissionsViaRoles insteadOf HasPermissions;
        SpatieHasRoles:: getAllPermissions insteadOf HasPermissions;
        SpatieHasRoles:: givePermissionTo insteadOf HasPermissions;
        SpatieHasRoles:: syncPermissions insteadOf HasPermissions;
        SpatieHasRoles:: revokePermissionTo insteadOf HasPermissions;
        SpatieHasRoles:: getPermissionNames insteadOf HasPermissions;
        SpatieHasRoles:: getStoredPermission insteadOf HasPermissions;
        SpatieHasRoles:: ensureModelSharesGuard insteadOf HasPermissions;
        SpatieHasRoles:: getGuardNames insteadOf HasPermissions;
        SpatieHasRoles:: getDefaultGuardName insteadOf HasPermissions;
        SpatieHasRoles:: forgetCachedPermissions insteadOf HasPermissions;
    }
}