<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormPdLogistics extends Model
{
    protected $dates = ['created_at', 'updated_at', 'pickupDate', 'pickupTime', 'dropoffDate', 'dropoffTime', 'arrival', 'departure'];
    protected $guarded = [];
}
