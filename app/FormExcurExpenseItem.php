<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormExcurExpenseItem extends Model
{
    protected $dates = ['created_at', 'updated_at', 'required'];
    
    protected $guarded = [];
}
