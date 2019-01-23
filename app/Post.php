<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $timestamps = false;

    public $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function homeLatest()
    {
        return static::where('hidden', false)->orderByDesc('created_at')->take(3)->get();
    }

    public static function allAvailable()
    {
        return static::where('hidden', false)->orderByDesc('created_at')->paginate(9);
    }
}
