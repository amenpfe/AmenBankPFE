<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use Symfony\Component\HttpFoundation\Response;

class caiMiddleware
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
        if(Auth::user()->role != UserRole::byKey('ChefArchitectureIntegration')->getValue()) {
            return new Response(view('errors\unauthorized')->with('role', UserRole::byKey('ChefArchitectureIntegration')->getDescription()));
        }
        return $next($request);
    }
}