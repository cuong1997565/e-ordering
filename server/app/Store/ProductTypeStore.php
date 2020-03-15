<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\Grade;
use App\Models\Error;
use App\Models\ProductType;

class ProductTypeStore
{
    public function save(ProductType $productType) {
        if ($productType->id !== null && $productType->id !== 0)
        {
            return Error::NewAppError('store.product.type.save.existing.app_error',
                'ProductTypeStore.save', null, "id={$productType->id}",StatusBadRequest);
        }

        $productType->id = null;

        try
        {
            if(! $data = $productType->toInstanceArray())
            {
                return Error::NewAppError('store.product.type.save.app_error', 'ProductTypeStore.save', null,
                    'empty data to insert',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.product.type.save.app_error', 'ProductTypeStore.save', null,
                'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            $productType = ProductType::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.product.type.save.app_error', 'ProductTypeStore.save',
                null, $e->getMessage(), StatusInternalServerError);
        }

        return $productType;
    }

    //update id product type
    public function update(ProductType $productType)
    {
        if(!$productType->id || !is_integer($productType->id))
        {
            return Error::NewAppError('model.product.type.is_valid.id.app_error', 'ProductTypeStore.update', null,
                "id={$productType->id}",StatusBadRequest);
        }

        try {
            if (! ProductType::find($productType->id)) {
                return Error::NewAppError('store.product.type.update.find.app_error', 'ProductTypeStore.update',
                    null, "id=" . $productType->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.product.type.update.find.app_error', 'ProductTypeStore.update', null,
                "id=" . $productType->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if(!$data =  $productType->toInstanceArray())
            {
                return Error::NewAppError('store.product.type.update.app_error',
                    'ProductTypeStore.update', null, 'empty data to update',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.product.type.update.app_error',
                'ProductTypeStore.update', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            ProductType::where('id', $productType->id)->update($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.product.type.update.updating.app_error', 'ProductTypeStore.update', null,
                "id=" . $productType->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $productType;
    }

}
