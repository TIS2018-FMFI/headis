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

    public static function isAvailableDate()
    {
        return NotAvailableDate::whereDate('date', '=', Carbon::today())->get() == null;
    }
}
