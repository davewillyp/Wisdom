<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    //
    public function linktype()
    {
        return $this->belongsTo(Linktype::class);
    }
}
