<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckAdmin
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
        if (!$request->user())
            return redirect('login');
        elseif($request->user()->hasRole('admin'))
            return $next($request);
        else
            return redirect('403.html');
    }
}
