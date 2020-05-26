<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    public function items(){
        return $this->belongsToMany(Bookingitem::class)->withTimestamps();
    }

    public static function bookeditems($date,$period)
    {
        $bookeditems = Booking::join('booking_bookingitem', 'bookings.id', '=', 'booking_bookingitem.booking_id')
        ->join('bookingitems', 'booking_bookingitem.bookingitem_id', '=', 'bookingitems.id')
        ->join('bookingcategories','bookingitems.bookingcategory_id','=','bookingcategories.id')
        ->select('bookingitems.id')
        ->where('bookings.date_of',$date)
        ->where('bookings.period_id', $period)        
        ->get();

        return $bookeditems;
    }
    
}
