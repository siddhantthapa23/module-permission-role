<?php

namespace Modules\Administration\Repositories\Permission;

use Illuminate\Support\Collection;
use App\Repositories\BaseRepositoryEloquent;
use Modules\Administration\Entities\Permission;

class PermissionRepositoryEloquent extends BaseRepositoryEloquent implements PermissionRepository
{
    /**
     * PermissionRepositoryEloquent constructor
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        $this->model = $permission;
    }

    /**
     * @return Collection
     */
    public function groupByGuardName() : Collection
    {
        return $this->model->get()->groupBy('group_name');
    }
}