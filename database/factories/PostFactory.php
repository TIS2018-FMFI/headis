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

$factory->define(App\Post::class, function (Faker $faker) {
    $redactor= \App\User::where('isRedactor', true)->first();

    return [
        'title' => $faker->sentence,
        'text' => $faker->paragraph,
        'date_from' => now(),
        'date_to' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+2years' , $timezone = null),
        'image' => '//via.placeholder.com/350x150',
        'user_id' => $redactor->id,
    ];
});