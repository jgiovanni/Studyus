<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'admin',
            'display_name' => 'Administrators',
            'description' => 'User is allowed to manage all models.'
        ]);

        DB::table('roles')->insert([
            'name' => 'instructors',
            'display_name' => 'Teachers',
            'description' => 'User is allowed to manage classrooms, students, and homeworks.'
        ]);

        DB::table('roles')->insert([
            'name' => 'students',
            'display_name' => 'Students',
            'description' => 'User is allowed to complete homework assignments.'
        ]);
    }
}
