<?php

namespace Modules\Administration\Repositories\User;

use Illuminate\Database\QueryException;
use Modules\Administration\Entities\User;
use App\Repositories\BaseRepositoryEloquent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Administration\Exceptions\User\CreateUserErrorException;
use Modules\Administration\Exceptions\User\UserNotFoundException;
use Modules\Administration\Exceptions\User\UpdateUserErrorException;

class UserRepositoryEloquent extends BaseRepositoryEloquent implements UserRepository
{
    /**
     * UserRepositoryEloquent constructor
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @param array $data
     * @return User
     * @throws CreateUserErrorException
     */
    public function createUser(array $data): User
    {
        try {
            $data['password'] = (array_key_exists('password', $data)) ? bcrypt($data['password']) : '';
            $data['created_by'] = auth()->id();
            return $this->model->create($data);
        } catch (QueryException $e) {
            throw new CreateUserErrorException($e);
        }
    }

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findUser(int $id): User
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException($e);
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws UpdateUserErrorException
     */
    public function updateUser(array $data): bool
    {
        try {
            $data['updated_by'] = auth()->id();
            return $this->model->update($data);
        } catch (QueryException $e) {
            throw new UpdateUserErrorException($e);
        }
    }

    /**
     * @return bool
     */
    public function deleteUser(): ?bool
    {
        return $this->model->delete();
    }
}