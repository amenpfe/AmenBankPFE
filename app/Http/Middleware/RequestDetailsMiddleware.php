<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\ProjectRequest;
use App\Enums\UserRole;
use App\Enums\StatusRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\User;

class RequestDetailsMiddleware
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
        if(!Auth::check()) {
            return redirect('login');
        }
        $projectRequest = ProjectRequest::find($request->id);
        //$user_id = ProjectRequest::find($request->user_id); 
        $user = Auth::user();
        if($user->role == UserRole::byKey('User')->getValue() && $projectRequest->user_id == $user->id){
            return $next($request);

        } else if ($user->role == UserRole::byKey('CED')->getValue() && $projectRequest->status == StatusRequest::byKey('progressing_CED')->getValue()) {
            return $next($request);

        } else if ($user->role == UserRole::byKey('ChefCD')->getValue() && $projectRequest->status == StatusRequest::byKey('progressing_chd')->getValue()) {
            return $next($request);

        } else if ($user->role == UserRole::byKey('proprietaire')->getValue() && $projectRequest->status == StatusRequest::byKey('waiting')->getValue()) {
            return $next($request);

        } else if ($user->role == UserRole::byKey('dev_chef')->getValue() && $projectRequest->status == StatusRequest::byKey('progressing_div')->getValue()) {
            return $next($request);

        } else if ($user->role == UserRole::byKey('quality_chef')->getValue() && $projectRequest->status == StatusRequest::byKey('progressing_recette')->getValue()) {
            return $next($request);

        }else if ($user->role == UserRole::byKey('sys_chef')->getValue() && $projectRequest->status == StatusRequest::byKey('progressing_systeme')->getValue()) {
            return $next($request);}

        $trueUser = User::find($projectRequest->user_id);
        return new Response(view('errors\unauthorized')->with('role', UserRole::byValue($trueUser->role)->getDescription()));

        /*if ($user->role == UserRole::byKey('User')->getValue() && $projectRequest->status == StatusRequest::byKey('send')->getValue()) {
            return $next($request);
        }
        if ($user->role == UserRole::byKey('User')->getValue() && $projectRequest->status == StatusRequest::byKey('waiting')->getValue()) {
            return $next($request);
        }
        return new Response(view('errors\unauthorized')->with('role', 'Admin'));*/

    }
}
