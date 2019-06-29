<?php

namespace Tests\Feature\Backend\Role;

use Tests\TestCase;
use Modules\Administration\Entities\User;
use Modules\Administration\Entities\Permission;

class RoleTest extends TestCase
{
    /************************************* Positive tests ********************************************/

    /**
     * @test
     */
    public function it_can_show_the_index_role_page()
    {
        $permission = factory(Permission::class)->create(['name' => 'view role']);
        $user = factory(User::class)->create();
        $user->givePermissionTo($permission);

        $this->actingAs($user)
            ->get(route('administration.roles.index'))
            ->assertStatus(200)
            ->assertSee('SN')
            ->assertSee('Name')
            ->assertSee('Guard Name')
            ->assertSee('Options');
    }

    /**
     * @test
     */
    public function it_can_show_the_create_role_page()
    {
        $permissions = collect(['view role', 'create role'])->each(function($item) {
            return ($item == 'view role') ? factory(Permission::class)->create(['name' => $item]) : factory(Permission::class)->create(['name' => $item]);
        });

        $user = factory(User::class)->create();
        $user->givePermissionTo($permissions);

        $this->actingAs($user)
            ->get(route('administration.roles.create'))
            ->assertStatus(200)
            ->assertSee('Create Role')
            ->assertSee('Name')
            ->assertSee('Submit');
    }

    /**
     * @test
     */
    public function it_can_create_the_role()
    {
        $data = [
            'name' => $this->faker->name,
            'guard_name' => 'web',
            'group_name' => $this->faker->word
        ];
        
        $permissions = collect(['view role', 'create role'])->each(function($item) {
            return ($item == 'view role') ? factory(Permission::class)->create(['name' => $item]) : factory(Permission::class)->create(['name' => $item]);
        });

        $user = factory(User::class)->create();
        $user->givePermissionTo($permissions);

        $this->actingAs($user)
            ->post(route('administration.roles.store'), $data)
            ->assertStatus(302)
            ->assertRedirect(route('administration.roles.index'))
            ->assertSessionHas('success_message', 'Role has been created successfully.');
    }

    /************************************* Positive tests ********************************************/
}