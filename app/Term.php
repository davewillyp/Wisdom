<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $guarded = [];

    public function weeks()
    {
       return $this->hasMany(Termweek::class);
    }
}
