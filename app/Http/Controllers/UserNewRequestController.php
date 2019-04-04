<?php

namespace App\Http\Controllers;

use App\Request;
use App\NewProjectRequest;

class UserNewRequestController extends Controller
{
    public function index(){
        return view('user-new-request');
    }

    public function table (){
        return view('exemple')->with('requests', NewProjectRequest::all());
    }
}
