<?php

namespace Modules\Administration\Repositories\User;

use Modules\Administration\Entities\User;

interface UserRepository 
{
    public function createUser(array $data): User;

    public function findUser(int $id): User;

    public function updateUser(array $data): bool;

    public function deleteUser(): ?bool;

    public function changeStatus(int $id, int $status) : int;
}