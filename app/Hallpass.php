<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hallpass extends Model
{
    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'startTime', 'endTime', 'date'];
}
