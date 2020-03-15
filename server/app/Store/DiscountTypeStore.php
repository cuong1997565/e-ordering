<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\DiscountType;
use App\Models\Error;

class DiscountTypeStore
{

    //create id discount type
    public function save(DiscountType $discountType)
    {
        if ($discountType->id !== null && $discountType->id !== 0)
        {
            return Error::NewAppError('store.discount.type.save.existing.app_error', 'DiscountTypeStore.save', null, "id={$discountType->id}",StatusBadRequest);
        }

        $discountType->id = null;

        try
        {
            if(! $data = $discountType->toInstanceArray())
            {
                return Error::NewAppError('store.discount.type.save.app_error', 'DiscountTypeStore.save', null, 'empty data to insert',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.discount.type.save.app_error', 'DiscountTypeStore.save', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            $discountType = DiscountType::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.discount.type.save.app_error', 'DiscountTypeStore.save', null, $e->getMessage(), StatusInternalServerError);
        }

        return $discountType;
    }

    public function update(DiscountType $discountType) {
        if(!$discountType->id || !is_integer($discountType->id))
        {
            return Error::NewAppError('model.discount.type.is_valid.id.app_error', 'DiscountTypeStore.update', null,
                "id={$discountType->id}",StatusBadRequest);
        }

        try {
            if (! DiscountType::find($discountType->id)) {
                return Error::NewAppError('store.discount.type.update.find.app_error', 'DiscountTypeStore.update',
                    null, "id=" . $discountType->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.discount.type.update.find.app_error', 'DiscountTypeStore.update', null,
                "id=" . $discountType->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if(!$data =  $discountType->toInstanceArray())
            {
                return Error::NewAppError('store.discount.type.update.app_error',
                    'DiscountTypeStore.update', null, 'empty data to update',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.discount.type.update.app_error',
                'DiscountTypeStore.update', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            DiscountType::where('id', $discountType->id)->update($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.discount.type.update.updating.app_error', 'DiscountTypeStore.update', null,
                "id=" . $discountType->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $discountType;
    }

}
