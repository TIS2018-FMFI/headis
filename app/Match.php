<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    public $timestamps = false;

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function date()
    {
        return $this->belongsTo(Date::class);
    }

    public function sets()
    {
        return $this->hasMany(Challenge::class);
    }
}
