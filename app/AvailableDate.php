<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvailableDate extends Model
{
    public $timestamps = false;

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}
