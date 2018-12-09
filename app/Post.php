<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function homeLatest()
    {
        return static::whereDate('date_from', '<=', Carbon::now())->whereDate('date_to', '>=', Carbon::now())->orderByDesc('created_at')->take(3)->get();
    }

    public static function allAvailable()
    {
        return static::whereDate('date_from', '<=', Carbon::now())->whereDate('date_to', '>=', Carbon::now())->orderByDesc('created_at')->get();
    }
}
