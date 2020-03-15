<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\Attributes;
use App\Models\Error;
use App\Models\ProductType;
use App\Models\AttributeListsOfValue;

class AttributesStore
{
    //add attributes
    public function save(Attributes $attributes) {
        if ($attributes->id !== null && $attributes->id !== 0)
        {
            return Error::NewAppError('store.attributes.save.existing.app_error',
                'AttributesStore.save', null, "id={$attributes->id}",StatusBadRequest);
        }

        $attributes->id = null;

        try
        {
            if(! $data = $attributes->toInstanceArray())
            {
                return Error::NewAppError('store.attributes.save.app_error', 'AttributesStore.save', null,
                    'empty data to insert',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.attributes.save.app_error', 'AttributesStore.save', null,
                'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (! ProductType::find($attributes->product_type_id)) {
                return Error::NewAppError('store.stores.product.type.find.app_error', 'AttributesStore.save',
                    null, "id=" . $attributes->product_type_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.product.type.find.app_error', 'AttributesStore.save', null,
                "id=" . $attributes->product_type_id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            $attributes = Attributes::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.attributes.save.app_error', 'AttributesStore.save',
                null, $e->getMessage(), StatusInternalServerError);
        }

        return $attributes;
    }
    //update attributes
    public function update(Attributes $attributes)
    {

        //check product type id can not exit
        try {
            if (! ProductType::find($attributes->product_type_id)) {
                return Error::NewAppError('store.stores.product.type.find.app_error', 'AttributesStore.save',
                    null, "id=" . $attributes->product_type_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.product.type.find.app_error', 'AttributesStore.save', null,
                "id=" . $attributes->product_type_id . ', ' . $e->getMessage(), StatusInternalServerError);
        }


        try {
            if (! Attributes::find($attributes->id)) {
                return Error::NewAppError('store.uoms.update.find.app_error', 'AttributesStore.update',
                    null, "id=" . $attributes->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.attributes.update.find.app_error', 'AttributesStore.update', null,
                "id=" . $attributes->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if(!$data =  $attributes->toInstanceArray())
            {
                return Error::NewAppError('store.attributes.update.app_error',
                    'AttributesStore.update', null, 'empty data to update',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.attributes.update.app_error',
                'AttributesStore.update', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            Attributes::where('id', $attributes->id)->update($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.attributes.update.updating.app_error', 'AttributesStore.update', null,
                "id=" . $attributes->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $attributes;
    }

}
