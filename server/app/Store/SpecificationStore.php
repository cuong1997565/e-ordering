<?php

namespace App\Store;

use App\Models\Specification;
use App\Models\Error;

class SpecificationStore
{
    public function save(Specification $specification)
    {
        if ($specification->id !== null && $specification->id !== 0) {
            return Error::NewAppError('store.specification.save.existing.app_error', 'SpecificationStore.save', null, "id={$specification->id}", StatusBadRequest);
        }

        $specification->id = null;

        try {
            if (!$data = $specification->toInstanceArray()) {
                return Error::NewAppError('store.specification.save.app_error', 'SpecificationStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.specification.save.app_error', 'SpecificationStore.save', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            $specification = Specification::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.specification.save.app_error', 'SpecificationStore.save', null, $e->getMessage(), StatusInternalServerError);
        }
        return $specification;
    }

    public function update(Specification $specification)
    {
        if (!$specification->id || !is_integer($specification->id)) {
            return Error::NewAppError('model.specification.is_valid.id.app_error', 'SpecificationStore.update', null, "id={$specification->id}", StatusBadRequest);
        }

        try {
            if (!Specification::find($specification->id)) {
                return Error::NewAppError('store.specification.update.find.app_error', 'SpecificationStore.update', null, "id=" . $specification->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.specification.update.finding.app_error', 'SpecificationStore.update', null, "id=" . $specification->id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $specification->toInstanceArray()) {
                return Error::NewAppError('store.specification.save.app_error', 'SpecificationStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.specification.update.app_error', 'SpecificationStore.update', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            Specification::where('id', $specification->id)
                ->update($data);

        } catch (\Exception $e) {
            return Error::NewAppError('store.specification.update.updating.app_error', 'SpecificationStore.update', null, "id=" . $specification->id . $e->getMessage(), StatusInternalServerError);
        }

        return $specification;
    }
}
