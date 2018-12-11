<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 'email', 'password', 'position', 'image', 'first_name', 'last_name', 'isRedactor'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

    public function challenges()
    {
        return $this->challengesAsAsked()->union($this->challengesAsAsked());
    }

    public function challengesAsChallenger()
    {
        return $this->hasMany(Challenge::class,'user_id_1');
    }

    public function challengesAsAsked()
    {
        return $this->hasMany(Challenge::class,'user_id_2');
    }

    public function countOfChallengesAsChallenger()
    {
        return $this->challengesAsChallenger->whereMonth('created_date', Carbon::now())->count();
    }

    public function countOfChallengesAsAsked()
    {
        return $this->challengesAsAsked->whereMonth('created_date', Carbon::now())->count();
    }

    public static function currentChallenge(User $user)
    {
        return Challenge::where('user_id_1', $user->id)->orWhere('user_id_2', $user->id)->whereNotIn('id', Match::all()->pluck('challenge_id')->toArray())->get();
    }

    public static function currentMatch(User $user)
    {
        return $user->matches()->where('confirmed', false)->get();
    }

    public function matches()
    {
        return Match::whereIn('challenge_id', $this->challenges->pluck('id')->toArray());
    }
}
