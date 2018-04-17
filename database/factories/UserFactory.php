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
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'id' => $faker->unique()->uuid,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/**
 * Admin User
 */
$factory->defineAs(App\User::class, 'admin', function (Faker\Generator $faker) use ($factory)
{
    $user = $factory->raw(App\User::class);

    return array_merge($user, [
        'id' => '4a9d9f34-2e2e-46de-9d37-828dc180ede7',
        'email'    => 'admin@studyus.com'
    ]);
});

/**
 * Instructor User
 */
$factory->defineAs(App\User::class, 'instructor', function (Faker\Generator $faker) use ($factory)
{
    $user = $factory->raw(App\User::class);

    return array_merge($user, [
        'email'    => 'teach@teach.com'
    ]);
});

/**
 * Student User
 */
$factory->defineAs(App\User::class, 'student', function (Faker\Generator $faker) use ($factory)
{
    $user = $factory->raw(App\User::class);

    return $user;
});

