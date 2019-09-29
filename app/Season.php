<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notAvailableDates()
    {
        return $this->hasMany(NotAvailableDate::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public static function current()
    {
        return static::query()->where('date_from', '<=', Carbon::now())->where('date_to', '>=', Carbon::now())->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function available()
    {
        return static::query()->whereDate('date_to', '>=', Carbon::now())->get();
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

    public function pyramid()
    {
        return $this->points()->whereDate('date', $this->points()->max('date'))->join('users', 'points.user_id','=', 'users.id')
            ->select('points.point AS position', 'users.user_name', 'users.id');
    }
}
