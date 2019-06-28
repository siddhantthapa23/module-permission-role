<?php

namespace Modules\Administration\Repositories\Role;

use App\Repositories\BaseRepositoryEloquent;
use Modules\Administration\Entities\Role;

class RoleRepositoryEloquent extends BaseRepositoryEloquent implements RoleRepository
{
    /**
     * RoleRepositoryEloquent constructor
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        $this->model = $role;
    }
}