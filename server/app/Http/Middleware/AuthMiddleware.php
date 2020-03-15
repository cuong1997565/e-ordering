<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($request->curUser)
        {
            // Check session expired
            if (Cache::store('file')->has($request->curUser->auth_token)) {
                // Update last action
                Cache::store('file')->put($request->curUser->auth_token, 'online', SESSION_EXPIRED);
            } else {
                return response([MESSAGE => trans('Login Time-out')], 440);
            }

            return $next($request);
        }
        else
        {
            return response([MESSAGE=>trans('Invalid user authentication')], 401);
        }
    }
}
