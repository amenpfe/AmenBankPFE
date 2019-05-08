<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

class ChdMiddleware
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
        if(Auth::user()->role != UserRole::byKey('ChefCD')->getValue()) {
            return new Response(view('errors\unauthorized')->with('role', UserRole::byKey('ChefCD')->getDescription()));
        }
        return $next($request);
    }
}
