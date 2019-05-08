<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectRequest extends Model
{
    protected $table = 'requests';

    public function requestable()
    { 
        return $this->morphTo(); 
    }

}
