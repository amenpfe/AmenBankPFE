<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Enums\UserRole;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }

    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        switch ($user->role) {
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
    }
}
