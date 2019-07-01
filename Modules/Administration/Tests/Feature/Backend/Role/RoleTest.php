<?php

namespace Tests\Feature\Backend\Role;

use Tests\TestCase;
use Modules\Administration\Entities\Permission;
use Modules\Administration\Entities\Role;
use Modules\Administration\Entities\User;

class RoleTest extends TestCase
{
    protected $user;

    protected $role;

    /**
     * Setup the test
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create();
    }

    /**
     * @test
     */
    public function it_can_show_the_index_page()
    {
        $permission = factory(Permission::class)->create(['name' => 'view role']);
        
        $this->user->givePermissionTo($permission);

        $this->actingAs($this->user)
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
            return factory(Permission::class)->create(['name' => $item]);
        });
        
        $this->user->givePermissionTo($permissions);

        $this->actingAs($this->user)
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
            return factory(Permission::class)->create(['name' => $item]);
        });

        $this->user->givePermissionTo($permissions);

        $this->actingAs($this->user)
            ->post(route('administration.roles.store'), $data)
            ->assertStatus(302)
            ->assertRedirect(route('administration.roles.index'))
            ->assertSessionHas('success_message', 'Role has been created successfully.');
    }

    /**
     * @test
     */
    public function it_can_show_the_show_role_page()
    {
        $permission = factory(Permission::class)->create(['name' => 'view role']);
        
        $this->user->givePermissionTo($permission);

        $this->actingAs($this->user)
            ->get(route('administration.roles.show', $this->role->id))
            ->assertStatus(200)
            ->assertSee('Role Details')
            ->assertSee($this->role->name)
            ->assertSee($this->role->guard_name)
            ->assertSee('Permissions');
    }

    /**
     * @test
     */
    public function it_can_show_the_edit_role_page()
    {
        $permissions = collect(['view role', 'edit role'])->each(function($item) {
            return factory(Permission::class)->create(['name' => $item]);
        });
        
        $this->user->givePermissionTo($permissions);

        $this->actingAs($this->user)
            ->get(route('administration.roles.edit', $this->role->id))
            ->assertStatus(200)
            ->assertSee('Update Role')
            ->assertSee($this->role->name)
            ->assertSee('Submit');
    }

    /**
     * @test
     */
    public function it_can_edit_the_role()
    {
        $data = [
            'name' => $this->faker->name,
            'guard_name' => 'web',
            'group_name' => $this->faker->word
        ];

        $permissions = collect(['view role', 'edit role'])->each(function($item) {
            return factory(Permission::class)->create(['name' => $item]);
        });

        $this->user->givePermissionTo($permissions);

        $this->actingAs($this->user)
            ->put(route('administration.roles.update', $this->role->id), $data)
            ->assertStatus(302)
            ->assertRedirect(route('administration.roles.index'))
            ->assertSessionHas('success_message', 'Role has been created successfully.');
    }

    /**
     * @test
     */
    public function it_can_delete_the_role()
    {
        $permissions = collect(['view role', 'delete role'])->each(function($item) {
            return factory(Permission::class)->create(['name' => $item]);
        });
        
        $this->user->givePermissionTo($permissions);

        $this->actingAs($this->user)
            ->delete(route('administration.roles.destroy', $this->role->id))
            ->assertStatus(200)
            ->assertExactJson(['type' => 'success', 'message' => 'Role has been deleted successfully.']);
    }

    /**
     * @test
     */
    public function it_can_show_the_attach_permission_view_page()
    {
        $permission = factory(Permission::class)->create(['name' => 'view role']);

        $role = factory(Role::class)->create(['name' => 'admin']);
        
        $this->user->givePermissionTo($permission);
        $this->user->assignRole($role);

        $this->actingAs($this->user)
            ->get(route('administration.roles.attach-permission', $role->id))
            ->assertStatus(200)
            ->assertSee('Attach Permission')
            ->assertSee('Save');
    }

    /**
     * @test
     */
    public function it_can_attach_permission_to_role()
    {
        $requestData = [
            'permissions' => [1,2]
        ];

        $permission = factory(Permission::class)->create(['name' => 'view role']);

        $this->user->givePermissionTo($permission);

        $this->actingAs($this->user)
            ->post(route('administration.roles.attach-permission.store', $this->role->id), $requestData)
            ->assertStatus(302)
            ->assertRedirect(route('administration.roles.show', $this->role->id))
            ->assertSessionHas('success_message', 'Permission has been attached successfully.');
    }

    /**
     * @test
     */
    public function it_can_remove_permission_from_role()
    {
        $permission = factory(Permission::class)->create(['name' => 'view role']);

        $role = factory(Role::class)->create(['name' => 'admin']);

        $this->user->givePermissionTo($permission);
        $this->user->assignRole($role);

        $this->actingAs($this->user)
            ->delete(route('administration.roles.remove-permission', [$role->id, $permission->id]))
            ->assertStatus(200)
            ->assertExactJson(['type' => 'success', 'message' => 'Permission has been removed successfully.']);
    }
}