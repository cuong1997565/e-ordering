<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class AdminMiddleware
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
        if($request->curUser)
        {
            if($request->curUser->group == GROUP_ADMIN || $request->curUser->group == GROUP_SALE_FACTORY)
            {
                return $next($request);
            }
            else
            {
                return response([MESSAGE=>trans('Invalid user authorization')], 403);
            }
        }
    }
}
