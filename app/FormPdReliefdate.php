<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormPdReliefdate extends Model
{
    protected $dates = ['created_at', 'updated_at', 'startDate', 'startTime', 'finishDate', 'finishTime'];
    protected $guarded = [];
}
