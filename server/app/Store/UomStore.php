<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\Uom;
use App\Models\Error;

class UomStore
{
    public function save(Uom $uom) {
        if ($uom->id !== null && $uom->id !== 0)
        {
            return Error::NewAppError('store.uom.save.existing.app_error',
                'UomStore.save', null, "id={$uom->id}",StatusBadRequest);
        }

        $uom->id = null;

        try {
            if(((int)$uom->is_based_uom === IS_BASED_UOM_FALSE)) {
                $data = Uom::where('id', $uom->based_uom_id)->where('is_based_uom' ,IS_BASED_UOM_TRUE)->count();
                if($data <= 0) {
                    return Error::NewAppError('store.stores.based.uom.id.find.app_error', 'UomStore.save',
                        null, "id=" . $uom->based_uom_id, StatusBadRequest);
                }
            }
        } catch (\Exception $e) {

        }

        try
        {
            if(! $data = $uom->toInstanceArray())
            {
                return Error::NewAppError('store.uom.save.app_error', 'UomStore.save', null,
                    'empty data to insert',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.uom.save.app_error', 'UomStore.save', null,
                'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            $uoms = Uom::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.uom.save.app_error', 'UomStore.save',
                null, $e->getMessage(), StatusInternalServerError);
        }

        return $uoms;
    }

    //update id factory
    public function update(Uom $uom)
    {
        if(!$uom->id || !is_integer($uom->id))
        {
            return Error::NewAppError('model.uoms.is_valid.id.app_error', 'UomStore.update', null,
                "id={$uom->id}",StatusBadRequest);
        }

        try {
            if (! Uom::find($uom->id)) {
                return Error::NewAppError('store.uoms.update.find.app_error', 'UomStore.update',
                    null, "id=" . $uom->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.uoms.update.find.app_error', 'UomStore.update', null,
                "id=" . $uom->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if(((int)$uom->is_based_uom === IS_BASED_UOM_FALSE)) {
                $data = Uom::where('id', $uom->based_uom_id)->where('is_based_uom' ,IS_BASED_UOM_TRUE)->count();
                if($data <= 0) {
                    return Error::NewAppError('store.stores.based.uom.id.find.app_error', 'UomStore.save',
                        null, "id=" . $uom->based_uom_id, StatusBadRequest);
                }
            }
        } catch (\Exception $e) {

        }

        try {
            if(!$data =  $uom->toInstanceArray())
            {
                return Error::NewAppError('store.uoms.update.app_error',
                    'UomStore.update', null, 'empty data to update',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.uoms.update.app_error',
                'UomStore.update', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            Uom::where('id', $uom->id)->update($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.factory.update.updating.app_error', 'UomStore.update', null,
                "id=" . $uom->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $uom;
    }

}
