<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Response;

class DsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::check())
        {
            return redirect('login');
        }
        if(Auth::user()->role != UserRole::byKey('sys_chef')->getValue()) {
            return new Response(view('errors\unauthorized')->with('role', UserRole::byKey('sys_chef')->getDescription()));
        }
        return $next($request);
    }
}
