<?php

namespace App\Http\Middleware;

use App\App\CustomerRepository;
use App\App\SessionRepository;
use App\Models\User;
use App\Models\Error;
use App\Providers\Logger\Facade\AppLogger;
use App\Store\CustomerStore;
use App\Store\SessionStore;
use Carbon\Carbon;
use Closure;

class MemberMiddleware
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
        $token = $request->input('e_auth_token');

        if (!$token || !is_string($token) || (is_string($token) && strlen($token) == 0)) {
            $error = Error::NewAppError('api.middleware.session_expired.app_error', '', [], "UserRequired", StatusUnauthorized);
            $error->Message = trans('messages.'.$error->Id, $error->Params);
            AppLogger::LogError($error);
            AppLogger::WriteLogError();
            response()->json($error, StatusUnauthorized)->send(); die;
        }

        $session = (new SessionRepository())->getSession($token);

        if ((is_object($session)) && get_class($session) == Error::class) {
            $status = $session->StatusCode;
            AppLogger::LogError($session);
            AppLogger::WriteLogError();
            if (is_array($session->Params)) {
                $session->Message = trans('messages.'.$session->Id, $session->Params);
            } else {
                $session->Message = trans('messages.'.$session->Id);
            }
            response()->json($session, $status)->send(); die;
        }

        if ($this->getDiffInMinutes($session->last_activity_at) >= CLIENT_SESSION_EXPIRED) {
            $result = (new SessionRepository())->revokeById($session->id);

            if ((is_object($result)) && get_class($result) == Error::class) {
                AppLogger::LogError($result);
                AppLogger::WriteLogError();
            }
            $error = Error::NewAppError('api.middleware.session_expired.app_error', '', [], "UserRequired", StatusUnauthorized);
            $error->Message = trans('messages.'.$error->Id, $error->Params);
            AppLogger::LogError($error);
            AppLogger::WriteLogError();
            response()->json($error, StatusUnauthorized)->send(); die;

        }

        if ($this->getDiffInMinutes($session->last_activity_at) > LAST_ACTIVITY_TIMEOUT) {
            $session->last_activity_at = Carbon::now();

            $result = (new SessionRepository())->updateSession($session);

            if ((is_object($result) && get_class($result) === Error::class)) {
                $status = StatusUnauthorized;
                if (is_array($session->Params)) {
                    $session->Message = trans('messages.'.$session->Id, $session->Params);
                } else {
                    $session->Message = trans('messages.'.$session->Id);
                }
                response()->json($session, $status)->send(); die;
            }
        }

        $request->curSession = $session;

        return $next($request);
    }

    protected function getDiffInMinutes($time)
    {
        $now = Carbon::parse(date('Y/m/d H:i:s', time()));

        return $now->diffInMinutes($time);
    }
}
