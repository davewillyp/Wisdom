<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eventtype extends Model
{
    public function event()
    {
        return $this->hasMany(Event::class);
    }
}
