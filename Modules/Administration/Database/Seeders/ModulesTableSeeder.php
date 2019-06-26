<?php

namespace Modules\Administration\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert([
            /** Administration modules */
            [
                /** 1 */
                'parent_id' => 0,
                'name' => 'Administration',
                'guard_name' => 'web',
                'icon' => 'fa fa-cog',
                'order_position' => '1',
                'status' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                /** 2 */
                'parent_id' => 1,
                'name' => 'Users',
                'guard_name' => 'web',
                'icon' => 'fa fa-users',
                'order_position' => '1',
                'status' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                /** 3 */
                'parent_id' => 1,
                'name' => 'Roles',
                'guard_name' => 'web',
                'icon' => 'fa fa-user',
                'order_position' => '2',
                'status' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            /** Administration modules */
        ]);
    }
}
