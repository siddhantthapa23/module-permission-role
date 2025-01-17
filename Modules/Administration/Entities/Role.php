<?php

namespace Modules\Administration\Entities;


use Modules\Administration\Traits\HasModules;
use Spatie\Permission\Models\Role as SpatieRole;
use Modules\Administration\Traits\RefreshesModuleCache;
use Modules\Administration\Traits\ExtendedHasPermissions;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends SpatieRole
{
    use HasModules;
    use RefreshesModuleCache;
    use ExtendedHasPermissions;

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
}