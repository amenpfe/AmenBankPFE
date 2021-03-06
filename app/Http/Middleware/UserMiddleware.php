<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
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
        if(Auth::user()->role != UserRole::byKey('User')->getValue()) {
            return new Response(view('errors\unauthorized')->with('role', UserRole::byKey('User')->getDescription()));
        }
        return $next($request);
    }
}
