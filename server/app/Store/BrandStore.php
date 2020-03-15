<?php

namespace App\Store;

use App\Models;
use App\Models\Brand;
use App\Models\Error;

class BrandStore
{
    public function save(Brand $brand)
    {
        if ($brand->id !== null && $brand->id !== 0) {
            return Error::NewAppError('store.brand.save.existing.app_error', 'BrandStore.save', null, "id={$brand->id}", StatusBadRequest);
        }

        $brand->id = null;

        try {
            if (!$data = $brand->toInstanceArray()) {
                return Error::NewAppError('store.brand.save.app_error', 'BrandStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.brand.save.app_error', 'BrandStore.save', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            $brand = Brand::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.brand.save.app_error', 'BrandStore.save', null, $e->getMessage(), StatusInternalServerError);
        }
        return $brand;
    }

    public function update(Brand $brand)
    {
        if (!$brand->id || !is_integer($brand->id)) {
            return Error::NewAppError('model.brand.is_valid.id.app_error', 'BrandStore.update', null, "id={$brand->id}", StatusBadRequest);
        }

        try {
            if (!Brand::find($brand->id)) {
                return Error::NewAppError('store.brand.update.find.app_error', 'BrandStore.update', null, "id=" . $brand->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.brand.update.finding.app_error', 'BrandStore.update', null, "id=" . $brand->id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $brand->toInstanceArray()) {
                return Error::NewAppError('store.brand.save.app_error', 'BrandStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.brand.update.app_error', 'BrandStore.update', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            Brand::where('id', $brand->id)
                ->update($data);

        } catch (\Exception $e) {
            return Error::NewAppError('store.brand.update.updating.app_error', 'BrandStore.update', null, "id=" . $brand->id . $e->getMessage(), StatusInternalServerError);
        }

        return $brand;
    }

    public function searchByName($name)
    {
        if (!is_string($name)) {
            return Error::NewAppError('model.brand.is_valid.name.app_error', 'BrandStore.searchByName', null, "name={$name}", StatusBadRequest);
        }
        try {
            $result = Brand::where('name', 'like', $name . '%')->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.brand.search_by_name.app_error', 'BrandStore.searchByName', ['Name' => $name], 'name='. $name . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $result;
    }

    public function getByName($name)
    {
        if(!is_string($name)) {
            return Error::NewAppError('model.brand.is_valid.name.app_error','BrandStore.getByName', null, "name={$name}",StatusBadRequest);
        }

        $name = urldecode($name);
        try {
            $result = Brand::where('name', $name)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.brand.get_by_name.app_error', 'BrandStore.getByName', ['Name' => $name], 'name='. $name . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $result;
    }
}
