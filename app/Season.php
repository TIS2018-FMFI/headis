<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function notAvailableDates()
    {
        return $this->hasMany(NotAvailableDate::class);
    }

    public static function current()
    {
        return static::query()->where('date_from', '<=', Carbon::now())->where('date_to', '>=', Carbon::now())->first();
    }

    public static function available()
    {
        return static::query()->whereDate('date_to', '>=', Carbon::now())->get();
    }
}
