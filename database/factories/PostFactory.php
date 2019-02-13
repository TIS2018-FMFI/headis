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
        'text' => $faker->paragraphs(3, true),
        'intro_text' => $faker->paragraph,
        'image' => null,
        'user_id' => $redactor->id,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now()
    ];
});
