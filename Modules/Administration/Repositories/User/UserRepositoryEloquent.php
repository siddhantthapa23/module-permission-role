<?php

namespace Modules\Administration\Repositories\User;

use App\Repositories\BaseRepositoryEloquent;
use Modules\Administration\Entities\User;

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
}