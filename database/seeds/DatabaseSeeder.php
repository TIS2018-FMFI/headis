<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->delete();
        DB::table('users')->insert(array(
            'user_name' => 'matej24',
            'email' => 'test@test.sk',
            'email_verified_at' => now(),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
            'position' => 0,
            'first_name' => 'Matej',
            'last_name' => 'Rychtarik',
            'isRedactor' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ));

        \App\Season::create([
            'date_from' => \Carbon\Carbon::createFromDate(2018, 9, 1),
            'date_to' => \Carbon\Carbon::createFromDate(2019, 6, 1),
        ]);

        factory(App\User::class, 20)->create()->each(function ($user) {
            static $point = 1;
            App\Point::create([
                'user_id' => $user->id,
                'date' => \Carbon\Carbon::now(),
                'point' => $point,
                'season_id' => \App\Season::current()->id,
            ]);
            App\Point::create([
                'user_id' => $user->id,
                'date' => \Carbon\Carbon::now()->addMonth(1),
                'point' => $point,
                'season_id' => \App\Season::current()->id,
            ]);
            App\Point::create([
                'user_id' => $user->id,
                'date' => \Carbon\Carbon::now()->addMonth(2),
                'point' => $point++,
                'season_id' => \App\Season::current()->id,
            ]);
        });



        factory(App\Match::class, 30)->create()->each(function ($match) {
            App\Set::create([
                'match_id' => $match->id,
                'score_1' => 11,
                'score_2' => 6
            ]);
            App\Set::create([
                'match_id' => $match->id,
                'score_1' => 11,
                'score_2' => 7
            ]);
        });

        factory(App\Post::class, 10)->create();



    }
}
