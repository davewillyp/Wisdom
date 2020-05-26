<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookingcategory extends Model
{
    protected $guarded = [];
    
    public function bookingitems()
    {
        return $this->hasMany(Bookingitem::class);
    }
}
