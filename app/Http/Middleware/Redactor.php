<?php

namespace App\Http\Middleware;

use Closure;

class Redactor
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
        if (!auth()->user()->isRedactor) {
            return redirect('/');
        }

        return $next($request);
    }
}
