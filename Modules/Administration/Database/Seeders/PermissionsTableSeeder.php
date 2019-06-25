<?php

namespace Modules\Administration\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Administration\Entities\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** 
         * Administration module permissions 
         */

            /** User permissions */
            Permission::create([
                'name' => 'view user',
                'guard_name' => 'web',
                'group_name' => 'users',
            ]);

            Permission::create([
                'name' => 'create user',
                'guard_name' => 'web',
                'group_name' => 'users',
            ]);

            Permission::create([
                'name' => 'edit user',
                'guard_name' => 'web',
                'group_name' => 'users',
            ]);

            Permission::create([
                'name' => 'delete user',
                'guard_name' => 'web',
                'group_name' => 'users',
            ]);
            /** User permissions end */

            /** Role permissions */
            Permission::create([
                'name' => 'view role',
                'guard_name' => 'web',
                'group_name' => 'roles',
            ]);

            Permission::create([
                'name' => 'create role',
                'guard_name' => 'web',
                'group_name' => 'roles',
            ]);

            Permission::create([
                'name' => 'edit role',
                'guard_name' => 'web',
                'group_name' => 'roles',
            ]);

            Permission::create([
                'name' => 'delete role',
                'guard_name' => 'web',
                'group_name' => 'roles',
            ]);
            /** Role permissions end */

        /** 
         * Administration module permissions end
         */
    }
}
