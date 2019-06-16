<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

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
        if(Auth::user() == null) {
            return redirect()->route("login");
        }
        switch (Auth::user()->role) {
            case UserRole::byKey('Admin')->getValue():
                return redirect()->route('manage_users');
                break;
            case UserRole::byKey('User')->getValue():
                return redirect()->route('get_new');
                break;
            case UserRole::byKey('CED')->getValue():
                return redirect()->route('get_ced_new');
                break;
            case UserRole::byKey('ChefCD')->getValue():
                return redirect()->route('get_chd_new');
                break;
            case UserRole::byKey('proprietaire')->getValue():
                return redirect()->route('get_prop_opt');
                break;
            case UserRole::byKey('dev_chef')->getValue():
                return redirect()->route('get_cdd_new');
                break;
            case UserRole::byKey('quality_chef')->getValue():
                return redirect()->route('get_cdq_new');
                break;
            case UserRole::byKey('sys_chef')->getValue():
                return redirect()->route('get_ds_new');
                break;
            case UserRole::byKey('info')->getValue():
                return redirect()->route('get_org_new');
                break;
            case UserRole::byKey('ChefArchitectureIntegration')->getValue():
                return redirect()->route('get_cai_new');
                break;
            case UserRole::byKey('Developpeur')->getValue():
                return redirect()->route('get_dv_new');
                break;    
            default:
                # code...
                break;
        }

        //return view('home');
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
