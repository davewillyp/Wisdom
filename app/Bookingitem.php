<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookingitem extends Model
{
    protected $guarded = [];
    
    public function bookingcategory()
    {
        return $this->belongsTo(Bookingcategory::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class);
    }
    
}
