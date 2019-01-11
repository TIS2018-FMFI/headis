<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id_1', 'user_id_2', 'created_at'
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
        return $this->hasMany(Date::class);
    }

    public function match()
    {
        return $this->hasOne(Match::class);
    }
}
