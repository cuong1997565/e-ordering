<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\UomMultiple;
use App\Models\Uom;
use App\Models\Error;

class UomMultiplesStore
{
    public function save(UomMultiple $uomMultiple) {
        if ($uomMultiple->id !== null && $uomMultiple->id !== 0)
        {
            return Error::NewAppError('store.uom.save.existing.app_error',
                'UomMultiplesStore.save', null, "id={$uomMultiple->id}",StatusBadRequest);
        }

        $uomMultiple->id = null;

        try {
            if (!Uom::find($uomMultiple->uom_id)) {
                return Error::NewAppError('store.stores.uom.find.app_error', 'UomMultiplesStore.save',
                    null, "id=" . $uomMultiple->uom_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.uom.find.app_error', 'UomMultiplesStore.save',
                null, "id=" . $uomMultiple->uom_id. $e->getMessage(), StatusInternalServerError);
        }


        try
        {
            if(! $data = $uomMultiple->toInstanceArray())
            {
                return Error::NewAppError('store.uom.save.app_error', 'UomMultiplesStore.save', null,
                    'empty data to insert',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.uom.save.app_error', 'UomMultiplesStore.save', null,
                'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            $uomMultiple = UomMultiple::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.uom.save.app_error', 'UomMultiplesStore.save',
                null, $e->getMessage(), StatusInternalServerError);
        }

        return $uomMultiple;
    }

    public function update(UomMultiple $uomMultiple) {
        if (!$uomMultiple->id || !is_integer($uomMultiple->id)) {
            return Error::NewAppError('model.uom.multiple.is_valid.id.app_error', 'UomMultiplesStore.update',
                null, "id={$uomMultiple->id}", StatusBadRequest);
        }

        try {
            if (!Uom::find($uomMultiple->uom_id)) {
                return Error::NewAppError('store.stores.uom.find.app_error', 'UomMultiplesStore.save',
                    null, "id=" . $uomMultiple->uom_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.uom.find.app_error', 'UomMultiplesStore.save',
                null, "id=" . $uomMultiple->uom_id. $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (! UomMultiple::find($uomMultiple->id)) {
                return Error::NewAppError('store.uom.multiple.update.app_error', 'UomMultiplesStore.update',
                    null, "id=" . $uomMultiple->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.uoms.update.find.app_error', 'UomStore.update', null,
                "id=" . $uomMultiple->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $uomMultiple->toInstanceArray()) {
                return Error::NewAppError('store.uom.multiple.save.app_error', 'UomMultiplesStore.update',
                    null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.uom.multiple.save.app_error', 'UomMultiplesStore.update', null,
                'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }

            $uomMultiple = UomMultiple::find($uomMultiple->id)->update($data);

        } catch (\Exception $e) {
            return Error::NewAppError('store.uom.multiple.update.app_error', 'UomMultiplesStore.update',
                null, $e->getMessage(), StatusInternalServerError);
        }

        return $uomMultiple;
    }

}
