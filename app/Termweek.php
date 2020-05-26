<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Termweek extends Model
{
    protected $guarded = [];

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public static function currentweek($today)
    {
        return Termweek::where('start', '<=', $today)
                    ->where ('end', '>=', $today)
                    ->first();

    }
}
