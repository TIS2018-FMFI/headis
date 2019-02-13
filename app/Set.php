<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function match()
    {
        return $this->belongsTo(Match::class);
    }
}
