<?php

namespace App;


class NewProjectRequest extends ProjectRequest
{
    protected $table = "newProjectrequests";

    public function request()
    { 
        return $this->morphOne('App\ProjectRequest', 'requestable'); 
    }
}
