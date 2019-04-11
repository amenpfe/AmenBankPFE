<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptimizationRequest extends ProjectRequest
{
    protected $table="optimizationrequests";
 
    public function request()
    { 
        return $this->morphOne('App\ProjectRequest', 'requestable'); 
    }
}
