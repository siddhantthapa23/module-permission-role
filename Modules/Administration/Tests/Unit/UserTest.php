<?php

namespace Tests\Unit;

use Tests\TestCase;
use Modules\Administration\Entities\User;
use Modules\Administration\Repositories\User\UserRepositoryEloquent;

class UserTest extends TestCase
{
    protected $user;

    /**
     * Setup the test
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /************************************************** Positive Tests **************************************************/

    /**
     * @test
     */
    public function it_can_create_a_user()
    {
        $data = [
            'first_name' => $this->faker->firstName('male'),
            'middle_name' => $this->faker->word,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->numberBetween($min = 0, $max = 2),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'avatar' => $this->faker->image($dir = '/tmp', $width = 640, $height = 480),
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
            'status' => 1,
            'remember_token' => $this->faker->md5,
        ];

        $userRepo = new UserRepositoryEloquent(new User);
        $user = $userRepo->createUser($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['first_name'], $user->first_name);
        $this->assertEquals($data['middle_name'], $user->middle_name);
        $this->assertEquals($data['last_name'], $user->last_name);
        $this->assertContains($user->gender, ['Male', 'Female', 'Other']);
        $this->assertEquals($data['address'], $user->address);
        $this->assertEquals($data['phone'], $user->phone);
        $this->assertEquals($data['avatar'], $user->avatar);
        $this->assertEquals($data['email'], $user->email);
        $this->assertContains($user->status, ['Inactive', 'Active']);
    }

    /**
     * @test
     */
    public function it_can_show_user()
    {
        $userRepo = new UserRepositoryEloquent(new User);
        $found = $userRepo->findUser($this->user->id);

        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($this->user->first_name, $found->first_name);
        $this->assertEquals($this->user->middle_name, $found->middle_name);
        $this->assertEquals($this->user->last_name, $found->last_name);
        $this->assertEquals($this->user->gender, $found->gender);
        $this->assertEquals($this->user->address, $found->address);
        $this->assertEquals($this->user->phone, $found->phone);
        $this->assertEquals($this->user->avatar, $found->avatar);
        $this->assertEquals($this->user->email, $found->email);
        $this->assertEquals($this->user->password, $found->password);
        $this->assertEquals($this->user->status, $found->status);
        $this->assertEquals($this->user->created_by, $found->created_by);
        $this->assertEquals($this->user->updated_by, $found->updated_by);
    }

    /**
     * @test
     */
    public function it_can_update_user()
    {
        $data = [
            'first_name' => $this->faker->firstName('male'),
            'middle_name' => $this->faker->word,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->numberBetween($min = 0, $max = 2),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'avatar' => $this->faker->image($dir = '/tmp', $width = 640, $height = 480),
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
            'status' => 1,
            'remember_token' => $this->faker->md5,
        ];

        $userRepo = new UserRepositoryEloquent($this->user);
        $updated = $userRepo->updateUser($data);

        $this->assertTrue($updated);
        $this->assertEquals($data['first_name'], $this->user->first_name);
        $this->assertEquals($data['middle_name'], $this->user->middle_name);
        $this->assertEquals($data['last_name'], $this->user->last_name);
        $this->assertContains($this->user->gender, ['Male', 'Female', 'Other']);
        $this->assertEquals($data['address'], $this->user->address);
        $this->assertEquals($data['phone'], $this->user->phone);
        $this->assertEquals($data['avatar'], $this->user->avatar);
        $this->assertEquals($data['email'], $this->user->email);
        $this->assertEquals($data['password'], $this->user->password);
        $this->assertContains($this->user->status, ['Inactive', 'Active']);
    }

    /**
     * @test
     */
    public function it_can_delete_user()
    {
        $userRepo = new UserRepositoryEloquent($this->user);
        $deleted = $userRepo->deleteUser();

        $this->assertTrue($deleted);
    }

    /************************************************** Positive Tests **************************************************/
}