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
$factory->define(App\Answer::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->unique()->uuid,
        'body' => $faker->sentence(10),
        'correct' => $faker->boolean(33),
        'question_id' => function () {
            return factory(App\Question::class)->create()->id;
        }
    ];
});
