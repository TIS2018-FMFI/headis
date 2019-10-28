<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}
