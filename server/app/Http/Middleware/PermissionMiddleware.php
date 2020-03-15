<?php

namespace App\Http\Middleware;

use App\App\AuthorizationRepository;
use App\Models\Error;
use App\Models\Permission;
use App\Models\User;
use Closure;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $authorization = new AuthorizationRepository($request);
        $curUserId = $request->curUser->id;
        if (!$authorization->userHasPermissionToDistributor($permission)) {
            $error = Error::NewPermissionError($curUserId, $permission);
            response()->json($error, StatusForbidden)->send(); die;
        }
        return $next($request);
    }
}
