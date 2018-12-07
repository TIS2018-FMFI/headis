<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    public $timestamps = false;

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
