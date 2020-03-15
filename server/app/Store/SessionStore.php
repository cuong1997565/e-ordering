<?php

namespace App\Store;

use App\App\SessionRepository;
use App\Models;
use App\Models\Session;
use App\Models\Error;

class SessionStore
{
    public function save(Session $session)
    {
        if ($session->id !== null && $session->id !== 0) {
            return Error::NewAppError('store.session.save.existing.app_error', 'SessionStore.save', null, "id={$session->id}", StatusBadRequest);
        }

        $session->id = null;

        try {
            if (!$data = $session->toInstanceArray()) {
                return Error::NewAppError('store.session.save.app_error', 'SessionStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.session.save.app_error', 'SessionStore.save', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            $session = $session->toArray();
            $session = Session::create($session);
        } catch (\Exception $e) {
            return Error::NewAppError('store.session.save.app_error', 'SessionStore.save', null, $e->getMessage(), StatusInternalServerError);
        }
        return $session;
    }

    public function get($sessionIdOrToken)
    {
        if (!filter_var($sessionIdOrToken, FILTER_VALIDATE_INT) !== false && !is_string($sessionIdOrToken)) {
            return Error::NewAppError('model.session.is_valid.token_or_id.app_error', 'SessionStore.get', null, "name='Id or Token'", StatusBadRequest);
        }

        try {
            $result = Session::where('id', $sessionIdOrToken)->orWhere('token', $sessionIdOrToken)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.session.get.app_error', 'SessionStore.get', null, "sessionIdOrToken=" . $sessionIdOrToken . ", " . $e->getMessage(), StatusInternalServerError);
        }

        if (is_null($result)) {
            return Error::NewAppError('store.session.get.app_error', 'SessionStore.get', null, "sessionIdOrToken=" . $sessionIdOrToken, StatusNotFound);
        }

        return $result;
    }

    public function update(Session $session)
    {
        if (!$session->id || !is_integer($session->id)) {
            return Error::NewAppError('model.session.is_valid.id.app_error', 'SessionStore.update', null, "id={$session->id}", StatusBadRequest);
        }

        try {
            if (!Session::find($session->id)) {
                return Error::NewAppError('store.session.update.find.app_error', 'SessionStore.update', null, "id=" . $session->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.session.update.finding.app_error', 'SessionStore.update', null, "id=" . $session->id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $session->toInstanceArray()) {
                return Error::NewAppError('store.session.save.app_error', 'SessionStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.session.update.app_error', 'SessionStore.update', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            Session::where('id', $session->id)
                ->update($data);

        } catch (\Exception $e) {
            return Error::NewAppError('store.session.update.updating.app_error', 'SessionStore.update', null, "id=" . $session->id . $e->getMessage(), StatusInternalServerError);
        }

        return $session;
    }

    public function deleteByCustomerId($customerId)
    {
        if (!$customerId || !is_integer($customerId)) {
            return Error::NewAppError('model.session.is_valid.id.app_error', 'SessionStore.deleteByCustomerId', null, "customer_id={$customerId}", StatusBadRequest);
        }

        try {
            $result =  Session::where('customer_id',$customerId)->get();
            foreach ($result as $session)
            {
                $this->delete($session);
                if ((is_object($session)) && get_class($session) == Session::class) {
                    (new SessionRepository())->deleteSessionToCache($session);
                }
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.session.get.app_error', 'SessionStore.deleteByCustomerId', null, "customer_id=" . $customerId . $e->getMessage(), StatusInternalServerError);
        }

        return true;
    }

    public function delete(Session $session)
    {
        if (!$session->id || !is_integer($session->id)) {
            return Error::NewAppError('model.session.is_valid.id.app_error', 'SessionStore.delete', null, "id={$session->id}", StatusBadRequest);
        }

        try {
            $session->delete();

        } catch (\Exception $e) {
            return Error::NewAppError('store.session.delete.deleting.app_error', 'SessionStore.delete', null, "id=" . $session->id . $e->getMessage(), StatusInternalServerError);
        }

        return $session;
    }
}
