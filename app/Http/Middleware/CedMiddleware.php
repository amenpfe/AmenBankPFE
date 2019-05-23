<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CedMiddleware
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
        if(Auth::user()->role != UserRole::byKey('CED')->getValue()) {
            return new Response(view('errors\unauthorized')->with('role', UserRole::byKey('CED')->getDescription()));
        }
        return $next($request);
    }
    
}
