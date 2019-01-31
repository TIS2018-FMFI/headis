<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NotAvailableDate extends Model
{
    public $timestamps = false;

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    /**
     * @param Carbon|null $date
     * @param bool $includeWeekend
     * @return bool
     */
    public static function isAvailableDate(Carbon $date = null, $includeWeekend = false)
    {
        $isWeekend = true;

        if ($date == null) {
            $date = Carbon::today('Europe/Bratislava');
        }
        if ($includeWeekend) {
            $isWeekend = !$date->isWeekend();
        }
        return !self::whereDate('date', $date)->exists() && $isWeekend;
    }

    /**
     * @param Carbon $start
     * @param int $days
     * @return Carbon
     */
    public static function addDaysTo(Carbon $start, $days)
    {
        $resultDate = $start->copy();

        while ($days > 0) {
            if (self::isAvailableDate($resultDate->addDay(), true)) {
                $days--;
            }
        }

        return $resultDate;
    }

    public static function getNotAvailableDatesInRange(Carbon $start, Carbon $end)
    {
        return self::where('season_id', Season::current()->id)->whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->get();
    }




}
