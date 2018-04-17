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
use Vinkla\Hashids\Facades\Hashids;

$factory->define(App\Task::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->unique()->uuid,
        'name' => $faker->sentence(3),
        'description' => $faker->sentence(),
        'public' => $faker->boolean(),
        'closed_at' => $faker->dateTimeBetween('30 days', '40 days'),
        'due_at' => $faker->dateTimeBetween('10 days', '20 days'),
        'opened_at' => $faker->dateTimeBetween('-10 days', 'now'),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
//        'type' => $faker->randomElement(['homework', 'quiz']),
        'url_id' => Hashids::encode($faker->unique()->numberBetween(1000000000, 9999999999)),
    ];
});
