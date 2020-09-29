<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormPd extends Model
{
    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'startDate', 'startTime', 'finishDate', 'finishTime'];

    public function relief(){
        return $this->hasMany(FormPdRelief::class);
    }

    
}
