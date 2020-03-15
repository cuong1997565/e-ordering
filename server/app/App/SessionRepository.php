<?php

namespace App\App;

use App\Models\Session;
use App\Models\Error;
use App\Store\SessionStore;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SessionRepository
{
    public function createSession($customer_id)
    {
        $token = sha1('[' . $customer_id . '-' . date('Y-m-d H:i:s') . ']');

        $dt = Carbon::now();
        $dt->addHours(24);

        // Prams: instance of Session
        $session = new \App\Models\Session();
        $session->expired_at = $dt;
        $session->customer_id = $customer_id;
        $session->token = $token;
        $session->last_activity_at = Carbon::now();
        $session = (new SessionStore())->save($session);

        if (is_object($session) && get_class($session) == Session::class) {
            $this->addSessionToCache($session);
        }
        return $session;
    }

    public function updateSession($session)
    {
        $result = (new SessionStore())->update($session);

        if ((is_object($result) && get_class($result) === Session::class)) {
            $this->addSessionToCache($session);
        }

        return $result;
    }

    public function revokeAllByCustomerId($customerId)
    {
        $result = (new SessionStore())->deleteByCustomerId($customerId);

        return $result;
    }

    public function revokeById($sessionId)
    {
        $result = (new SessionStore())->get($sessionId);

        if ((is_object($result) && get_class($result) === Session::class)) {
            $result = (new SessionStore())->delete($result);
        } else {
            return $result;
        }

        if ((is_object($result) && get_class($result) === Session::class)) {
            $this->deleteSessionToCache($result);
        }

        return $result;
    }

    public function getSession($token)
    {
        $session = Cache::store('file')->get($token);
        if (is_object($session) && get_class($session) == Session::class) {
            return $session;
        }

        $sessionOrError = (new SessionStore())->get($token);
        if ((is_object($sessionOrError)) && get_class($sessionOrError) == Error::class) {
            $sessionOrError->StatusCode = StatusUnauthorized;
        }
        return $sessionOrError;
    }

    public function addSessionToCache(Session $session)
    {
        if (env('CACHE_DRIVER') == 'file') {
            Cache::store('file')->put($session->token, $session, SESSION_EXPIRED);
        }
    }

    public function deleteSessionToCache(Session $session)
    {
        if (env('CACHE_DRIVER') == 'file') {
            Cache::store('file')->delete($session->token);
        }
    }
}
