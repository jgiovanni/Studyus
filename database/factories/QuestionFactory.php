<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Question::class, function (Faker\Generator $faker) {
    static $incrementer;
    return [
        'id' => $faker->unique()->uuid,
        'body' => $faker->sentence(5),
        'explanation' => $faker->sentence(10),
        'position_number' => $incrementer ? ++$incrementer : 1,
        'task_id' => function () {
            return factory(App\Task::class)->create()->id;
        }
    ];
});
