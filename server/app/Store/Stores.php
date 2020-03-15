<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\Factory;
use App\Models\Store;
use App\Models\Error;

class Stores
{
    public  function  save(Store $store)
    {
        if ($store->id !== null && $store->id !== 0) {
            return Error::NewAppError('store.stores.save.existing.app_error', 'Stores.save', null, "id={$store->id}",StatusBadRequest);
        }

        $store->id = null;
        try{
            if (! $data = $store->toInstanceArray()){
                return Error::NewAppError('store.stores.save.app_error','Stores.save',null,'empty data to insert',StatusInternalServerError);
            }
        }
        catch (\Exception $e)
        {
            return Error::NewAppError('store.stores.save.app_error', 'Stores.save', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (! Factory::find($store->factory_id)) {
                return Error::NewAppError('store.stores.factory.find.app_error', 'Stores.save', null, "id=" . $store->dimi_factory, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.factory.find.app_error', 'Stores.save', null, "id=" . $store->dimi_factory . $e->getMessage(), StatusInternalServerError);
        }

        try {
            $store = Store::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.save.app_error', 'Stores.save', null, $e->getMessage(), StatusInternalServerError);
        }

        return $store;
    }

    public function update(Store $store)
    {
        if(!$store->id || !is_integer($store->id))
        {
            return Error::NewAppError('model.stores.is_valid.id.app_error', 'Stores.update', null, "id={$store->id}",StatusBadRequest);
        }

        try {
            if (! Store::find($store->id)) {
                return Error::NewAppError('store.stores.update.find.app_error', 'Stores.update', null, "id=" . $store->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.update.find.app_error', 'Stores.update', null, "id=" . $store->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try{
            if (! $data = $store->toInstanceArray()) {
                return Error::NewAppError('store.stores.update.app_error','Stores.update',null,'empty data to update',StatusInternalServerError);
            }
        }
        catch (\Exception $e)
        {
            return Error::NewAppError('store.stores.update.app_error', 'Stores.update', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (! Factory::find($store->factory_id)) {
                return Error::NewAppError('store.stores.factory.find.app_error', 'Stores.update', null, "id=" . $store->dimi_factory, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.factory.find.app_error', 'Stores.update', null, "id=" . $store->dimi_factory . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            Store::where('id', $store->id)->update($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.update.updating.app_error', 'Stores.update', null, "id=" . $store->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $store;

    }

    public function  getStoreByDistributorId($distributor_id)
    {
        try {
            $result = Store::where('distributor_id', $distributor_id)->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.distributor.get_by_name.app_error', 'Stores.getStoreByDistributorId', ['Name' => $distributor_id], 'name='. $distributor_id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        if(!$result)
        {
            return Error::NewAppError('store.distributor.get_by_name.first.app_error', 'Stores.getStoreByDistributorId', ['Name' => $distributor_id], "name={$distributor_id}",StatusNotFound);
        }

        return $result;
    }
}