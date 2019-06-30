<?php

namespace Modules\Administration\Repositories\Permission;

use Illuminate\Support\Collection;

interface PermissionRepository 
{
    public function groupByGuardName() : Collection;
}