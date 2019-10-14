<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function points()
    {
        return $this->hasMany(Point::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Query\Builder
     */
    public function challenges()
    {
        return $this->challengesAsChallenger()->union($this->challengesAsAsked());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function challengesAsChallenger()
    {
        return $this->hasMany(Challenge::class,'user_id_1');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function challengesAsAsked()
    {
        return $this->hasMany(Challenge::class,'user_id_2');
    }

    /**
     * @return int
     */
    public function countOfChallengesAsChallenger()
    {
        return $this->challengesAsChallenger()->whereMonth('created_date', Carbon::now())
            ->whereYear('created_date', Carbon::now())->count();
    }

    /**
     * @return int
     */
    public function countOfChallengesAsAsked()
    {
        return $this->challengesAsAsked()->whereMonth('created_date', Carbon::now())
            ->whereYear('created_date', Carbon::now())->count();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public static function currentChallenge(User $user)
    {
        return Challenge::where(function ($query) use ($user) {
            $query->where('user_id_1', $user->id);
            $query->orWhere('user_id_2', $user->id);
        })->whereNotIn('id', Match::all()->pluck('challenge_id')->toArray())->first();
    }

    /**
     * @return mixed
     */
    public function currentMatch()
    {
        return Match::where('confirmed', null)->whereIn('matches.challenge_id', $this->challenges->pluck('id')->toArray())
            ->first();
    }

    /**
     * @param $season_id
     * @return mixed
     */
    public function matches($season_id = null)
    {
        return Match::select('matches.*')
            ->where('confirmed', true)
            ->where('type','normal')
            ->when($season_id, function ($query, $season_id) {
                return $query->where('season_id', $season_id);
            })
            ->whereIn('matches.challenge_id', $this->challenges->pluck('id')->toArray())
            ->join('dates', 'matches.date_id','=','dates.id')
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * @return int
     */
    public function allMatches()
    {
        return count($this->matches());
    }

    /**
     * @return int
     */
    public function allWonMatches()
    {
        return count($this->matches());
    }

    /**
     * @return mixed
     */
    public static function pyramid()
    {
        return User::where('isRedactor', false)->orderBy('position')->get();
    }

    /**
     * @param null $columns
     * @return array
     */
    public static function canDeactivate($columns = null)
    {
        if ($columns == null) {
            $columns = ['*'];
        }

        if (Season::current() == null) {
            return User::where('isRedactor',false)->get($columns);
        }


        $allUsers = User::where('isRedactor',false)->get($columns);

        $users = [];

        foreach ($allUsers as $user) {
            if (self::currentChallenge($user) == null && $user->currentMatch() == null) {
                $users[] = $user;
            }
        }

        return $users;
    }

    /**
     * @param null $columns
     * @return mixed
     */
    public static function canActivate($columns = null)
    {
        if ($columns == null) {
            $columns = ['*'];
        }

        return User::onlyTrashed()->get($columns);
    }

    /**
     * @return mixed
     */
    public function matchWithNotPenalized()
    {
        return Match::select('matches.*')->where('confirmed', true)->where('type','notPenalized')
            ->whereIn('matches.challenge_id', $this->challengesAsAsked->pluck('id')->toArray())
            ->get();
    }

    /**
     * @return mixed
     */
    public static function redactor()
    {
        return self::where('isRedactor', true)->first();
    }
}
