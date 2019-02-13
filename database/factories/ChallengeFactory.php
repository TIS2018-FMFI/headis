<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Challenge::class, function (Faker $faker) {
    $user1 = \App\User::where('id', '!=', 1)->inRandomOrder()->first();
    $user2 = \App\User::where('id', '!=', 1)->where('id', '!=', $user1->id)->inRandomOrder()->first();

    return [
        'user_id_1' => $user1->id,
        'user_id_2' => $user2->id,
        'created_date' => $faker->dateTimeThisYear()
    ];
});