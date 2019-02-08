<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Date extends Model
{
    use SoftDeletes;

    public $timestamps = false;
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * @param Carbon $start
     * @param Carbon $end
     * @return mixed
     */
    public static function getDatesInRange(Carbon $start, Carbon $end)
    {
        return self::whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->get();
    }
}
