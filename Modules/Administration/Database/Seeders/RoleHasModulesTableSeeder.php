<?php

namespace Modules\Administration\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleHasModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_has_modules')->insert([
            [
                'module_id' => 1,
                'role_id' => 1
            ],
            [
                'module_id' => 2,
                'role_id' => 1
            ],
            [
                'module_id' => 3,
                'role_id' => 1
            ],
        ]);
    }
}
