<?php

namespace Tests\Unit\Role;

use Tests\TestCase;
use Modules\Administration\Entities\Role;
use Modules\Administration\Repositories\Role\RoleRepositoryEloquent;
use Modules\Administration\Exceptions\Role\CreateRoleErrorException;
use Modules\Administration\Exceptions\Role\RoleNotFoundException;
use Modules\Administration\Exceptions\Role\UpdateRoleErrorException;

class RoleTest extends TestCase
{
    /********************************** Positive tests ********************************************/

    /**
     * @test
     */
    public function it_can_create_role()
    {
        $data = [
            'name' => $this->faker->word,
            'guard_name' => 'web'
        ];

        $roleRepo = new RoleRepositoryEloquent(new Role);
        $role = $roleRepo->createRole($data);

        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals($data['name'], $role->name);
        $this->assertEquals($data['guard_name'], $role->guard_name);
    }

    /**
     * @test
     */
    public function it_can_show_role()
    {
        $role = factory(Role::class)->create();
        $roleRepo = new RoleRepositoryEloquent(new Role);
        $found = $roleRepo->findRole($role->id);

        $this->assertInstanceOf(Role::class, $found);
        $this->assertEquals($role->name, $found->name);
        $this->assertEquals($role->guard_name, $found->guard_name);
    }

    /**
     * @test
     */
    public function it_can_update_role()
    {
        $role = factory(Role::class)->create();

        $data = [
            'name' => $this->faker->word,
            'guard_name' => 'web'
        ];

        $roleRepo = new RoleRepositoryEloquent($role);
        $update = $roleRepo->updateRole($data);

        $this->assertTrue($update);
        $this->assertEquals($data['name'], $role->name);
        $this->assertEquals($data['guard_name'], $role->guard_name);
    }

    /**
     * @test
     */
    public function it_can_delete_role()
    {
        $role = factory(Role::class)->create();

        $roleRepo = new RoleRepositoryEloquent($role);
        $delete = $roleRepo->deleteRole();

        $this->assertTrue($delete);
    }

    /********************************** Positive tests ********************************************/

    /********************************** Negative tests ********************************************/

    /**
     * @test
     */
    public function it_should_throw_an_error_when_the_required_columns_are_not_filled()
    {
        $this->expectException(CreateRoleErrorException::class);

        $roleRepo = new RoleRepositoryEloquent(new Role);
        $roleRepo->createRole([]);
    }

    /**
     * @test
     */
    public function it_should_throw_not_found_error_exception_when_role_is_not_found()
    {
        $this->expectException(RoleNotFoundException::class);

        $roleRepo = new RoleRepositoryEloquent(new Role);
        $roleRepo->findRole(999);
    }

    /**
     * @test
     */
    public function it_should_throw_update_error_exception_when_role_has_failed_to_update()
    {
        $this->expectException(UpdateRoleErrorException::class);

        $role = factory(Role::class)->create();
        $roleRepo = new RoleRepositoryEloquent($role);

        $data = ['name' => null];
        $roleRepo->updateRole($data);
    }

    /**
     * @test
     */
    public function it_returns_null_when_deleting_a_non_existing_role()
    {
        $roleRepo = new RoleRepositoryEloquent(new Role);
        $delete = $roleRepo->deleteRole();

        $this->assertNull($delete);
    }

    /********************************** Negative tests ********************************************/
}