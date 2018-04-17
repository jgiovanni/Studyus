<?php

use Illuminate\Database\Seeder;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Task::unsetEventDispatcher();

        $instructors = App\User::whereHas('roles', function ($q) {
            return $q->where('name', 'instructors');
        })->get();

        // create task models
        $instructors->each(function ($user) {
            // create and save a task model
            $user->task_assignments()->saveMany(factory(App\Task::class, 2)->make(['user_id' => $user->id])
                ->each(function ($task) use ($user) {
                    // create and save question models to task model
                    $questions = factory(App\Question::class, 5)->make(['task_id' => $task->id]);

                    // create and save answer models to each question models
                    $questions->each(function ($question) {
                        $answers = factory(App\Answer::class, 3)->make(['question_id' => $question->id]);
                        $question->answers()->saveMany($answers);
                    });

                    // save last
                    $task->questions()->saveMany($questions);
                }));
        });

        App\Task::setEventDispatcher(new \Illuminate\Events\Dispatcher);;

    }
}
