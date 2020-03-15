<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\Distributor;
use App\Models\Factory;
use App\Models\Error;

class FactoryStore
{
    //list id factory
    public function getAll()
    {
        try
        {
            $result = Factory::getDynamic();
        }
        catch (\Exception $e)
        {

        }
        return $result;
    }
    
    //create id factory
    public function save(Factory $factories)
    {
        if ($factories->id !== null && $factories->id !== 0)
        {
            return Error::NewAppError('store.factory.save.existing.app_error', 'FactoryStore.save', null, "id={$factories->id}",StatusBadRequest);
        }

        $factories->id = null;

        try
        {
            if(! $data = $factories->toInstanceArray())
            {
                return Error::NewAppError('store.factory.save.app_error', 'FactoryStore.save', null, 'empty data to insert',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.factory.save.app_error', 'FactoryStore.save', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            $factories = Factory::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.factory.save.app_error', 'FactoryStore.save', null, $e->getMessage(), StatusInternalServerError);
        }

        return $factories;
    }

    //update id factory
    public function update(Factory $factories)
    {
        if(!$factories->id || !is_integer($factories->id))
        {
                return Error::NewAppError('model.factory.is_valid.id.app_error', 'FactoryStore.update', null, "id={$factories->id}",StatusBadRequest);
        }

        try {
            if (! Factory::find($factories->id)) {
                return Error::NewAppError('store.factory.update.find.app_error', 'FactoryStore.update', null, "id=" . $factories->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
                return Error::NewAppError('store.factory.update.find.app_error', 'FactoryStore.update', null, "id=" . $factories->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if(!$data =  $factories->toInstanceArray())
            {
                return Error::NewAppError('store.factory.update.app_error', 'FactoryStore.update', null, 'empty data to update',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.factory.update.app_error', 'FactoryStore.update', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
           Factory::where('id', $factories->id)->update($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.factory.update.updating.app_error', 'FactoryStore.update', null, "id=" . $factories->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $factories;
    }

    //get id factory
    public function get($id)
    {
        if (! is_integer($id))
        {
            return Error::NewAppError('model.factory.is_valid.id.app_error', 'FactoryStore.update', null, "id={$id}",StatusBadRequest);
        }

        try {
            $factory = Factory::find($id);
        } catch (\Exception $e) {
            return Error::NewAppError('store.factory.get.finding.app_error', 'FactoryStore.get', null, "id=" . $id . ', ' . $e->getMessage(),StatusInternalServerError);
        }

        if($factory == null)
        {
            return Error::NewAppError('store.factory.get.finding.app_error', 'FactoryStore.get', null, "id={$id}",StatusNotFound);

        }

        return $factory;
    }

    public function getAllWithFields($fields = [])
    {
        try {
            $factories = Factory::select($fields)->where('active', ACTIVE_TRUE)->orderBy('name','asc')->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.factory.get.finding.app_error', 'FactoryStore.get', null, "id=" . $id . ', ' . $e->getMessage(),StatusInternalServerError);
        }

        if(!$factories)
        {
            return Error::NewAppError('store.factory.getCategoriesForSelect.finding.app_error', 'FactoryStore.getCategoriesForSelect', null, "",StatusNotFound);
        }

        return $factories;
    }

    public function countFactoriesBySaleOrderIds($saleOrderIds) {
        if (!is_array($saleOrderIds)) {
            return Error::NewAppError('store.factory_store.count_factories_by_sale_order_ids.invalid_sale_orders', 'DistributorStore.countFactoriesBySaleOrderIds', null, '', StatusBadRequest);
        }

        try {
            $count = Factory::join('sale_orders', 'sale_orders.factory_id', '=', 'factories.id')
                ->whereIn('sale_orders.id', $saleOrderIds)
                ->select('factories.*')
                ->groupBy('factories.id')
                ->get()->count();
        } catch (\Exception $e) {
            return Error::NewAppError('store.factory_store.count_factories_by_sale_order_ids.app_error', 'DistributorStore.countDistributorBySaleOrderIds', null, $e->getMessage(), StatusInternalServerError);
        }

        return $count;
    }
}
