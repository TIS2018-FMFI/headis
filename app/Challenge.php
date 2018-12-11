<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    public $timestamps = false;


    public function challenger()
    {
        return $this->belongsToMany(User::class, 'users', 'user_id_1', 'user_id_2');
    }

    public function asked()
    {
        return $this->belongsToMany(User::class, 'users', 'user_id_2', 'user_id_1');
    }

    public function players()
    {
        return $this->challenger()->union($this->asked()->getBaseQuery());
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function dates()
    {
        return $this->hasMany(Date::class);
    }

    public function match()
    {
        return $this->hasOne(Match::class);
    }
}
