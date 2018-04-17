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
$factory->define(App\ClassRoom::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->unique()->uuid,
        'name' => $faker->sentence(2),
        'grades' => $faker->randomElements(['Pre-K', 'Kindergarten', '1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th'], 2),
        'subject' => $faker->randomElement(['Math', 'Science', 'Social Studies', 'Physics', 'Chemistry', 'Biology', 'Health & Medicine', 'Engineering', 'History', 'Art', 'English Literature', 'Language', 'Other']),
        'url_id' => Hashids::encode($faker->unique()->numberBetween(1000000000, 9999999999)),
    ];
});
