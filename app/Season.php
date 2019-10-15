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
        return static::query()->where('date_from', '<=', Carbon::today()->toDateString())->where('date_to', '>=', Carbon::today()->toDateString())->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function available()
    {
        return static::query()->whereDate('date_to', '>=', Carbon::today()->toDateString())->get();
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

    public function getCurrentLabel()
    {
        $current = self::current();

        if ($current) {
            return Carbon::parse($current->date_from)->format('Y') . '/' . Carbon::parse($current->date_to)->format('Y');
        }
        return '';
    }

    public function getLabel()
    {
        return Carbon::parse($this->date_from)->format('Y') . '/' . Carbon::parse($this->date_to)->format('Y');
    }

    public function isCurrent()
    {
        return $this->id === self::current()->id;
    }

}
