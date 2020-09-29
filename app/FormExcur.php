<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormExcur extends Model
{
    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'startDate', 'startTime', 'finishDate', 'finishTime'];
}
