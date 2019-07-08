<?php

namespace Tests\Feature\Backend\User;

use Tests\TestCase;
use Modules\Administration\Entities\User;
use Modules\Administration\Entities\Role;
use Modules\Administration\Entities\Permission;
use Illuminate\Http\UploadedFile;

class UserTest extends TestCase
{
    protected $user;

    protected $role;

    /**
     * setup the test
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
        $permission = factory(Permission::class)->create(['name' => 'view user']);

        $this->user->givePermissionTo($permission);

        $this->actingAs($this->user)
            ->get(route('administration.users.index'))
            ->assertStatus(200)
            ->assertSee('Create User')
            ->assertSee('SN')
            ->assertSee('Name')
            ->assertSee('Email')
            ->assertSee('Status')
            ->assertSee('Options');
    }

    /**
     * @test
     */
    public function it_can_show_the_create_user_page()
    {
        $permissions = collect(['view user', 'create user'])->each(function($item) {
            return factory(Permission::class)->create(['name' => $item]);
        });

        $this->user->givePermissionTo($permissions);

        $this->actingAs($this->user)
            ->get(route('administration.users.create'))
            ->assertStatus(200)
            ->assertSee('User Details')
            ->assertSee('First Name')
            ->assertSee('Last Name')
            ->assertSee('Login Details')
            ->assertSee('Email')
            ->assertSee('Password')
            ->assertSee('Confirm Password');
    }

    /**
     * @test
     */
    public function it_can_create_the_user()
    {
        $password = $this->faker->password;

        $data = [
            'first_name' => $this->faker->firstName('male'),
            'middle_name' => $this->faker->word,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->numberBetween($min = 0, $max = 2),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'avatar' => UploadedFile::fake()->create('image.jpg'),
            'email' => $this->faker->unique()->safeEmail,
            'password' => $password,
            'password_confirmation' => $password,
            'status' => 1,
        ];
        
        $permissions = collect(['view user', 'create user'])->each(function($item) {
            return factory(Permission::class)->create(['name' => $item]);
        });

        $this->user->givePermissionTo($permissions);

        $this->actingAs($this->user)
            ->post(route('administration.users.store'), $data)
            ->assertStatus(302)
            ->assertRedirect(route('administration.users.index'))
            ->assertSessionHas('success_message', 'User has been created successfully.');
    }

    /**
     * @test
     */
    public function it_can_show_the_show_user_page()
    {
        $permission = factory(Permission::class)->create(['name' => 'view user']);

        $this->user->givePermissionTo($permission);

        $this->actingAs($this->user)
            ->get(route('administration.users.show', $this->user->id))
            ->assertStatus(200)
            ->assertSee('User Details')
            ->assertSee('Roles')
            ->assertSee('Permissions');
    }

    /**
     * @test
     */
    public function it_can_show_the_edit_user_page()
    {
        $permissions = collect(['view user', 'edit user'])->each(function($item) {
            return factory(Permission::class)->create(['name' => $item]);
        });

        $this->user->givePermissionTo($permissions);

        $this->actingAs($this->user)
            ->get(route('administration.users.edit', $this->user->id))
            ->assertStatus(200)
            ->assertSee('User Details')
            ->assertSee('First Name')
            ->assertSee('Last Name')
            ->assertSee('Login Details')
            ->assertSee('Email')
            ->assertSee('Password')
            ->assertSee('Confirm Password');
    }

    /**
     * @test
     */
    public function it_can_edit_the_user()
    {
        $password = $this->faker->password;

        $data = [
            'first_name' => $this->faker->firstName('male'),
            'middle_name' => $this->faker->word,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->numberBetween($min = 0, $max = 2),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'avatar' => UploadedFile::fake()->create('image.jpg'),
            'email' => $this->faker->unique()->safeEmail,
            'password' => $password,
            'password_confirmation' => $password,
            'status' => 1,
        ];

        $permissions = collect(['view user', 'edit user'])->each(function($item) {
            return factory(Permission::class)->create(['name' => $item]);
        });

        $this->user->givePermissionTo($permissions);

        $this->actingAs($this->user)
            ->put(route('administration.users.update', $this->user->id), $data)
            ->assertStatus(302)
            ->assertRedirect(route('administration.users.index'))
            ->assertSessionHas('success_message', 'User has been updated successfully.');
    }

    /**
     * @test
     */
    public function it_can_delete_the_user()
    {
        $permissions = collect(['view user', 'delete user'])->each(function($item) {
            return factory(Permission::class)->create(['name' => $item]);
        });

        $user = factory(User::class)->create();

        $this->user->givePermissionTo($permissions);

        $this->actingAs($this->user)
            ->delete(route('administration.users.destroy', $user->id))
            ->assertStatus(200)
            ->assertExactJson(['type' => 'Success', 'message' => 'User has been deleted successfully.']);
    }

    /**
     * @test
     */
    public function it_can_show_the_attach_role_view_page()
    {
        $permission = factory(Permission::class)->create(['name' => 'view user']);

        $role = factory(Role::class)->create(['name' => 'admin']);
        $role->givePermissionTo($permission);

        $this->user->assignRole($role);

        $this->actingAs($this->user)
            ->get(route('administration.users.attach-role', $this->user->id))
            ->assertStatus(200)
            ->assertSee('Attach Role')
            ->assertSee('Role')
            ->assertSee('Submit');
    }

    /**
     * @test
     */
    public function it_can_attach_role_to_user()
    {
        $requestData = [
            'roles' => [1]
        ];

        $permission = factory(Permission::class)->create(['name' => 'view user']);

        $this->user->givePermissionTo($permission);

        $this->actingAs($this->user)
            ->post(route('administration.users.attach-role.store', $this->user->id), $requestData)
            ->assertStatus(302)
            ->assertRedirect(route('administration.users.show', $this->user->id))
            ->assertSessionHas('success_message', 'Role has been attached successfully.');
    }

    /**
     * @test
     */
    public function it_can_remove_role_from_user()
    {
        $permission = factory(Permission::class)->create(['name' => 'view user']);

        $role = factory(Role::class)->create(['name' => 'admin']);
        $role->givePermissionTo($permission);

        $this->user->assignRole($role);

        $this->actingAs($this->user)
            ->delete(route('administration.users.remove-role', [$this->user->id, $this->role->name]))
            ->assertStatus(200)
            ->assertExactJson(['type' => 'Success', 'message' => 'Role has been removed successfully.']);
    }
    
    /**
     * @test
     */
    public function it_can_show_the_attach_permission_view_page()
    {
        $permission = factory(Permission::class)->create(['name' => 'view user']);

        $role = factory(Role::class)->create(['name' => 'admin']);
        $role->givePermissionTo($permission);

        $this->user->assignRole($role);

        $this->actingAs($this->user)
            ->get(route('administration.users.attach-permission', $this->user->id))
            ->assertStatus(200)
            ->assertSee('Attach Permission')
            ->assertSee('Save');
    }

    /**
     * @test
     */
    public function it_can_attach_permission_to_user()
    {
        $requestData = [
            'permissions' => [1,2]
        ];

        $permission = factory(Permission::class)->create(['name' => 'view user']);

        $this->user->givePermissionTo($permission);

        $this->actingAs($this->user)
            ->post(route('administration.users.attach-permission.store', $this->user->id), $requestData)
            ->assertStatus(302)
            ->assertRedirect(route('administration.users.show', $this->user->id))
            ->assertSessionHas('success_message', 'Permissions has been attached successfully.');
    }

    /**
     * @test
     */
    public function it_can_remove_permission_from_user()
    {
        $permission = factory(Permission::class)->create(['name' => 'view user']);

        $role = factory(Role::class)->create(['name' => 'admin']);
        $role->givePermissionTo($permission);

        $this->user->assignRole($role);

        $this->actingAs($this->user)
            ->delete(route('administration.users.remove-permission', [$this->user->id, $permission->id]))
            ->assertStatus(200)
            ->assertExactJson(['type' => 'Success', 'message' => 'Permission has been removed successfully.']);
    }

}