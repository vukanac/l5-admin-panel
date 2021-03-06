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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'role' => 'guest',
    ];
});

$factory->defineAs(App\User::class, 'owner', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, ['role' => 'owner']);
});

$factory->defineAs(App\User::class, 'admin', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, ['role' => 'admin']);
});

$factory->defineAs(App\User::class, 'manager', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, ['role' => 'manager']);
});

$factory->defineAs(App\User::class, 'author', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, ['role' => 'author']);
});

$factory->defineAs(App\User::class, 'viewer', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, ['role' => 'viewer']);
});


$factory->define(App\Company::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'email' => $faker->email,
    ];
});

$factory->define(App\Schedule::class, function (Faker\Generator $faker) {
    return [
        'run_at' => $faker->dateTimeBetween($startDate = '-1 month', $endDate = '+1 month'), // DateTime('2003-03-15 02:00:49'),
        'action' => App\SendApprovalEmail::class,
        'who_object' => App\Company::class,
        'who_id' => 1,
        'parameters' => json_encode(array()),
        'status' => 'new'
    ];
});
