<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Duty extends Model
{
    protected $guarded = [];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
