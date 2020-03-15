<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\Logger\Facade\AppLogger;
use Closure;

class AppMiddleware
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
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Expose-Headers: ETag');
        AppLogger::with($request->path(), app('uuid'), $request->ip(), null, $request->method());
        $data = $request->input();
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $data[$k] = json_encode($v);
            }
        }
        AppLogger::WriteLogInfo($data);
        $request->curUser = null;
        $request->curMember = null;
        $request->curSession = null;
        $request->curLang = env('APP_LOCALE');

        // Set current client language
        if ($request->input('lang')) {
            app('translator')->setLocale($request->input('lang'));
            $request->curLang = $request->input('lang');
        }

        if($request->input('token'))
        {
            $request->curUser = User::where('auth_token', $request->input('token'))->with('role')->first();
        }
//
//        /*if ($request->input('api_token')) // Member token
//        {
//            $request->curMember = Member::where('auth_token', $request->input('api_token'))->first();
//
//            if ($request->curMember) {
//                app('translator')->setLocale($request->curMember->lang);
//            }
//        }*/
//
//        if($_SERVER['REQUEST_URI'] == '/api/users/login')
//        {
//            $request->curUser = null;
//        }

        return $next($request);
    }
}
