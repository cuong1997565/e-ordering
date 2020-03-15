<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\PriceList;
use App\Models\Error;

class PriceListStore
{
    public function save(PriceList $priceList) {
        if ($priceList->id !== null && $priceList->id !== 0)
        {
            return Error::NewAppError('store.price.list.of.value.save.existing.app_error',
                'PriceListStore.save', null, "id={$priceList->id}",StatusBadRequest);
        }

        $priceList->id = null;
        try
        {
            if(! $data = $priceList->toInstanceArray())
            {
                return Error::NewAppError('store.price.list.of.value.save.app_error', 'PriceListStore.save', null,
                    'empty data to insert',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.price.list.of.value.save.app_error', 'PriceListStore.save', null,
                'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            $priceList = PriceList::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.price.list.of.value.save.app_error', 'PriceListStore.save',
                null, $e->getMessage(), StatusInternalServerError);
        }

        return $priceList;
    }

    public function update(PriceList $priceList)
    {
        if(!$priceList->id || !is_integer($priceList->id))
        {
            return Error::NewAppError('model.price.list.of.value.is_valid.id.app_error', 'PriceListStore.update', null,
                "id={$priceList->id}",StatusBadRequest);
        }

        $dataPriceList = PriceList::find($priceList->id);
        try {
            if (!$dataPriceList) {
                return Error::NewAppError('store.price.list.of.value.update.find.app_error', 'PriceListStore.update',
                    null, "id=" . $priceList->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.price.list.of.value.update.find.app_error', 'PriceListStore.update', null,
                "id=" . $priceList->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if(!$data =  $priceList->toInstanceArray())
            {
                return Error::NewAppError('store.price.list.of.value.update.app_error',
                    'PriceListStore.update', null, 'empty data to update',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.price.list.of.value.update.app_error',
                'PriceListStore.update', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            $dataPriceList->update($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.price.list.of.value.update.updating.app_error', 'UomStore.update', null,
                "id=" . $priceList->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $priceList;
    }
}
