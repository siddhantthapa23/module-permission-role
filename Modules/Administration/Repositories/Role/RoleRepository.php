<?php

namespace Modules\Administration\Repositories\Role;

use Modules\Administration\Entities\Role;

interface RoleRepository 
{
    public function createRole(array $data): Role;

    public function findRole(int $id): Role;

    public function updateRole(array $data): bool;

    public function deleteRole(): bool;
}