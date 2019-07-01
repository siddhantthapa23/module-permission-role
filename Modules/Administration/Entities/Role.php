<?php

namespace Modules\Administration\Entities;

use Spatie\Permission\Models\Role as SpatieRole;
use Modules\Administration\Traits\HasModules;
use Modules\Administration\Traits\RefreshesModuleCache;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Collection;

class Role extends SpatieRole
{
    use HasModules;
    use RefreshesModuleCache;

    protected $fillable = [
        'name',
        'guard_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     * 
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * A role may be given various modules.
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.module'),
            config('permission.table_names.role_has_modules'),
            'role_id',
            'module_id'
        );
    }

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