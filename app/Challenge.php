<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id_1', 'user_id_2', 'created_date'
    ];

    public function challenger()
    {
        return $this->belongsTo(User::class, 'user_id_1')->withTrashed();
    }

    public function asked()
    {
        return $this->belongsTo(User::class, 'user_id_2')->withTrashed();
    }

    public function players()
    {
        return $this->challenger()->union($this->asked());
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function dates()
    {
        return $this->hasMany(Date::class)->orderBy('date', 'ASC');
    }

    public function match()
    {
        return $this->hasOne(Match::class);
    }

    public function isMember()
    {
        return auth()->user()->id == $this->asked->id || auth()->user()->id == $this->challenger->id;
    }

    public static function current()
    {
        return self::query()->doesntHave('match')->get();
    }
}
