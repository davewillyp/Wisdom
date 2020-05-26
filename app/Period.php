<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    public function duties()
    {
       return $this->hasMany(Duty::class);
    }
}
