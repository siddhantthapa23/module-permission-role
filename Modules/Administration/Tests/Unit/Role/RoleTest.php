<?php

namespace Tests\Unit\Role;

use Tests\TestCase;
use Modules\Administration\Entities\Role;
use Modules\Administration\Repositories\Role\RoleRepositoryEloquent;

class RoleTest extends TestCase
{
    /********************************** Positive tests ********************************************/

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

    /********************************** Positive tests ********************************************/
}