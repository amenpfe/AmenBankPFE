<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class UserOptimizationRequestController extends Controller
{
    public function index(){
        return view('user-optimization-request')->with('projects', Project::all());
    }
}
