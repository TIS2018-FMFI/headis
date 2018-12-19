<?php

use App\User;
use Carbon\Carbon;
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

$factory->define(App\Match::class, function (Faker $faker) {
    $challenge = factory(App\Challenge::class)->create();

    $date = App\Date::create([
        'challenge_id' => $challenge->id,
        'date' => $challenge->created_date
    ]);

    App\Comment::create([
        'text' => $faker->sentence(),
        'challenge_id' => $challenge->id,
        'user_id' => $challenge->user_id_1,
        'date' => Carbon::now()
    ]);

    App\Comment::create([
        'text' => $faker->sentence(),
        'challenge_id' => $challenge->id,
        'user_id' => $challenge->user_id_2,
        'date' => Carbon::now()
    ]);

    return [
        'challenge_id' => $challenge->id,
        'date_id' => $date->id,
        'confirmed' => true
    ];
});