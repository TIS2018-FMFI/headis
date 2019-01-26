<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function date()
    {
        return $this->belongsTo(Date::class);
    }

    public function sets()
    {
        return $this->hasMany(Set::class)->orderBy('id');
    }

    public function winner()
    {
        $sets = $this->sets;
        $user1 = 0;
        foreach ($sets as $set){
            if ($set->score_1 > $set->score_2){
                $user1 = $user1 + 1;
            }
        }
        if ($user1 == 2){
            return $this->challenge->challenger();
        }
        return $this->challenge->asked();
    }

    public function finished()
    {
        $sets = $this->sets;
        $challenger_points = 0;
        $asked_points = 0;

        foreach ($sets as $set){
            if ($set->score_1 > $set->score_2){
                $challenger_points++;
            }
            else{
                $asked_points++;
            }
        }

        return ($asked_points == 2 || $challenger_points == 2);
    }

    public function isMember()
    {
        return auth()->user()->isRedactor || auth()->user()->id == $this->challenge->asked->id || auth()->user()->id == $this->challenge->challenger->id;
    }
}
