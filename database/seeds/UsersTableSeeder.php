<?php

use App\Task;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Vinkla\Hashids\Facades\Hashids;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 'admin')->create()->each(function($user) {
            $user->attachRole(1);
        });

        factory(App\User::class, 'instructor')->create()->each(function($user) {
            $user->attachRole(2);
        });

//        factory(App\User::class, 'student', 20)->create()->each(function($user) {
//            $user->attachRole(3);
//        });
    }
}
