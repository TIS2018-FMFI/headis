<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use SoftDeletes;


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


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
        return $this->challengesAsChallenger()->union($this->challengesAsAsked());
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
        return $this->challengesAsChallenger()->whereMonth('created_date', Carbon::now())->count();
    }

    public function countOfChallengesAsAsked()
    {
        return $this->challengesAsAsked()->whereMonth('created_date', Carbon::now())->count();
    }

    public static function currentChallenge(User $user)
    {
        return Challenge::where(function ($query) use ($user) {
            $query->where('user_id_1', $user->id);
            $query->orWhere('user_id_2', $user->id);
        })->whereNotIn('id', Match::all()->pluck('challenge_id')->toArray())->first();
    }

    public function currentMatch()
    {
        return $this->matches()->where('confirmed', false)->first();
    }

    public function matches()
    {
//        dd(Match::whereIn('matches.challenge_id', $this->challenges->pluck('id')->toArray())->join('dates', 'matches.date_id','=','dates.id')->
//        orderBy('date')->get());
        return Match::whereIn('matches.challenge_id', $this->challenges->pluck('id')->toArray())
            ->join('dates', 'matches.date_id','=','dates.id')
            ->orderBy('date')
            ->get();
    }


    public static function pyramid()
    {
        return User::where('isRedactor', false)->orderBy('position')->get();
    }
}
