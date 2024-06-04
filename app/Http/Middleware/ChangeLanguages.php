<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;

class ChangeLanguages
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
        config(['app.locale' => Session::get('language', config('app.locale'))]);
        
        // Add cache after login
        if(Auth::check())
        {
            if(Auth::user()->level != 9999)
            {
                if(!Session::has('roles') || Auth::user()->cache == 0)
                {
                    Session::forget('roles');
                    Session::put('roles', Auth::user()->role()->pluck('role')->toArray());
                    Auth::user()->update(['cache' => 1]);
                }
            }
        }

        return $next($request);
    }
}
