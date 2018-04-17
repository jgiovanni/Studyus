<?php

use Illuminate\Database\Seeder;

class ClassroomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\ClassRoom::unsetEventDispatcher();

        $instructors = App\User::whereHas('roles', function ($q) {
            return $q->where('name', 'instructors');
        })->get();

        // create classroom models
        $instructors->each(function ($instructor) {
            factory(App\ClassRoom::class, 4)->make()->each(function ($classroom) use ($instructor) {
                $instructor->classrooms()->attach($classroom->id, ['permissions' => 'manage']);

                // create 5 students attaching them to classroom
                factory(App\User::class, 'student', 5)->make()->each(function ($student) use ($classroom) {
                    $student->attachRole(3);
                    $student->classrooms()->attach($classroom->id, ['permissions' => null]);
                    $student->save();
                });
                //save classroom model last
                $classroom->save();
            });
        });

        App\ClassRoom::setEventDispatcher(new \Illuminate\Events\Dispatcher);
    }
}