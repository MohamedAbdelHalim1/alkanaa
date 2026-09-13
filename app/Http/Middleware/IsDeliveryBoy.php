<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsDeliveryBoy
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
        if (Auth::check() && Auth::user()->user_type == 'delivery_boy' && !Auth::user()->banned) {
            return $next($request);
        }
        else{
            abort(404);
        }
    }
}
