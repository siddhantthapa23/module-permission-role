<?php

namespace Modules\Administration\Repositories\Role;

use App\Repositories\BaseRepositoryEloquent;
use Modules\Administration\Entities\Role;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Administration\Exceptions\Role\CreateRoleErrorException;
use Modules\Administration\Exceptions\Role\RoleNotFoundException;
use Modules\Administration\Exceptions\Role\UpdateRoleErrorException;

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

    /**
     * @param array $data
     * @return Role
     * @throws CreateRoleErrorException
     */
    public function createRole(array $data): Role
    {
        try {
            return $this->model->create($data);
        } catch (\ErrorException $e) {
            throw new CreateRoleErrorException($e);
        }
    }

    /**
     * @param int $id
     * @return Role
     * @throws RoleNotFoundException
     */
    public function findRole(int $id): Role
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new RoleNotFoundException($e);
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws UpdateRoleErrorException
     */
    public function updateRole(array $data): bool
    {
        try {
            return $this->model->update($data);
        } catch (QueryException $e) {
            throw new UpdateRoleErrorException($e);
        }
    }

    /**
     * @return bool|null
     */
    public function deleteRole(): ?bool
    {
        return $this->model->delete();
    }
}