<?php

namespace App\Store;

use App\Models;
use App\Models\Category;
use App\Models\Error;

class CategoryStore
{
    public function save(Category $category)
    {
        if ($category->id !== null && $category->id !== 0) {
            return Error::NewAppError('store.category.save.existing.app_error', 'CategoryStore.save', null, "id={$category->id}", StatusBadRequest);
        }

        $category->id = null;

        try {
            if (!$data = $category->toInstanceArray()) {
                return Error::NewAppError('store.category.save.app_error', 'CategoryStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.category.save.app_error', 'CategoryStore.save', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            $category = Category::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.category.save.app_error', 'CategoryStore.save', null, $e->getMessage(), StatusInternalServerError);
        }
        return $category;
    }

    public function update(Category $category)
    {
        if (!$category->id || !is_integer($category->id)) {
            return Error::NewAppError('model.category.is_valid.id.app_error', 'CategoryStore.update', null, "id={$category->id}", StatusBadRequest);
        }

        try {
            if (!Category::find($category->id)) {
                return Error::NewAppError('store.category.update.find.app_error', 'CategoryStore.update', null, "id=" . $category->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.category.update.finding.app_error', 'CategoryStore.update', null, "id=" . $category->id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $category->toInstanceArray()) {
                return Error::NewAppError('store.category.save.app_error', 'CategoryStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.category.update.app_error', 'CategoryStore.update', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            Category::where('id', $category->id)
                ->update($data);

        } catch (\Exception $e) {
            return Error::NewAppError('store.category.update.updating.app_error', 'CategoryStore.update', null, "id=" . $category->id . $e->getMessage(), StatusInternalServerError);
        }

        return $category;
    }

    public function searchByName($name)
    {
        if (!is_string($name)) {
            return Error::NewAppError('model.category.is_valid.name.app_error', 'CategoryStore.searchByName', null, "name={$name}", StatusBadRequest);
        }
        try {
            $result = Category::where('name', 'like', $name . '%')->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.category.search_by_name.app_error', 'CategoryStore.searchByName', ['Name' => $name], 'name='. $name . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $result;
    }

    public function getByName($name)
    {
        if(!is_string($name)) {
            return Error::NewAppError('model.category.is_valid.name.app_error','CategoryStore.getByName', null, "name={$name}",StatusBadRequest);
        }

        $name = urldecode($name);
        try {
            $result = Category::where('name', $name)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.category.get_by_name.app_error', 'CategoryStore.getByName', ['Name' => $name], 'name='. $name . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $result;
    }
}
