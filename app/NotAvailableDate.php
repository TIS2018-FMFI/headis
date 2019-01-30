<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;

class NotAvailableDate extends Model
{
    public $timestamps = false;

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    /**
     * @param Carbon|null $date
     * @param boolean|null $includeWeekend
     * @return bool
     */
    public static function isAvailableDate(Carbon $date = null, boolean $includeWeekend = null)
    {
        $isWeekend = true;

        if ($date == null) {
            $date = Carbon::today('Europe/Bratislava');
        }
        if ($includeWeekend == null) {
            $isWeekend = !Carbon::today('Europe/Bratislava')->isWeekend();
        }
        return self::where('season_id', Season::current()->id)->whereDate('date', $date)->get() == null && $isWeekend;
    }

    /**
     * @param Carbon $start
     * @param integer $days
     * @return Carbon
     */
    public static function addDaysTo(Carbon $start, integer $days)
    {
        $resultDate = $start->copy();

        while ($days > 0) {
            if (self::isAvailableDate($resultDate->addDay())) {
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
