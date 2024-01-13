<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MustBeValidVoyagerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $route)
    { 
        
        if(Auth::check()) { 

            $user = Auth::user();
            app()->setLocale($user->locale ?? app()->getLocale());

            $urlDashBoard = route('voyager.dashboard');
            return $user->hasPermission('browse_'.$route) ? $next($request) : redirect($urlDashBoard);
        }

        $urlLogin = route('voyager.login');

        return redirect()->guest($urlLogin);
    }
}
