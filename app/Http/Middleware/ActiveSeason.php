<?php

namespace App\Http\Middleware;

use App\Season;
use Closure;

class ActiveSeason
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
        if (Season::current() == null){
            return redirect('/');
        }
        
        return $next($request);
    }
}
