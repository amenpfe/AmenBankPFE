<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Enums\UserRole;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   print_r(UserRole::getValues());
        return view('home');
    }

    public function addUser($name, $email, $password){
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->role = 0;
        $user->save();
    }
}
