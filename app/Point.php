<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
