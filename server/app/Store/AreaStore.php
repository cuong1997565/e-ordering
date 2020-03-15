<?php

namespace App\Store;

use App\Models\AppModel;
use App\Models\Area;
use App\Models\Error;

class AreaStore
{
    public function save(Area $area)
    {
        if ($area->id !== null && $area->id !== 0) {
            return Error::NewAppError('store.area.save.existing.app_error', 'AreaStore.save', null, "id={$area->id}", StatusBadRequest);
        }
        $area->id = null;
        try {
            if (!$data = $area->toInstanceArray()) {
                return Error::NewAppError('store.area.save.app_error', 'AreaStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.area.save.app_error', 'AreaStore.save', null, 'cannot convert instance to array ', StatusInternalServerError);
        }
        try {
            $area = Area::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.area.save.app_error', 'AreaStore.save', null, $e->getMessage(), StatusInternalServerError);
        }
        return $area;
    }

    public function update(Area $area)
    {
        if (!$area->id || !is_integer($area->id)) {
            return Error::NewAppError('model.area.is_valid.id.app_error', 'AreaStore.update', null, "id={$area->id}", StatusBadRequest);
        }

        try {
            if (!Area::find($area->id)) {
                return Error::NewAppError('store.area.update.find.app_error', 'AreaStore.update', null, "id=" . $area->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.area.update.finding.app_error', 'AreaStore.update', null, "id=" . $area->id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $area->toInstanceArray()) {
                return Error::NewAppError('store.area.save.app_error', 'AreaStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.area.update.app_error', 'AreaStore.update', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            Area::where('id', $area->id)
                ->update($data);

        } catch (\Exception $e) {
            return Error::NewAppError('store.area.update.updating.app_error', 'AreaStore.update', null, "id=" . $area->id . $e->getMessage(), StatusInternalServerError);
        }
        return $area;
    }

    public function getByName($name)
    {
        if (!is_string($name)) {
            return Error::NewAppError('model.area.is_valid.name.app_error', 'AreaStore.getByName', null, "name={$name}", StatusBadRequest);
        }
        //change nguyen%20van
        $name = urldecode($name);
        try {
            $result = Area::where('name', $name)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.area.get_by_name.app_error', 'AreaStore.getByName', ['Name' => $name], 'name=' . $name . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        if (!$result) {
            return Error::NewAppError('store.area.get_by_name.first.app_error', 'AreaStore.getByName', ['Name' => $name], "name={$name}", StatusNotFound);
        }

        return $result;
    }


    public function searchByName($name)
    {
        if (!is_string($name)) {
            return Error::NewAppError('model.area.is_valid.name.app_error', 'AreaStore.searchByName', null, "name={$name}", StatusBadRequest);
        }
        try {
            $result = Area::where('name', 'like', $name . '%')->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.area.search_by_name.app_error', 'AreaStore.searchByName', ['Name' => $name], 'name=' . $name . ', ' . $e->getMessage(), StatusInternalServerError);
        }
        return $result;
    }
}
