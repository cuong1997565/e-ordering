<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\Features;
use App\Models\Error;

class FeaturesStore
{
    public function save(Features $features) {
        if ($features->id !== null && $features->id !== 0)
        {
            return Error::NewAppError('store.features.save.existing.app_error',
                'FeaturesStore.save', null, "id={$features->id}",StatusBadRequest);
        }

        $features->id = null;

        try
        {
            if(! $data = $features->toInstanceArray())
            {
                return Error::NewAppError('store.features.save.app_error', 'FeaturesStore.save', null,
                    'empty data to insert',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.features.save.app_error', 'FeaturesStore.save', null,
                'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            $features = Features::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.features.save.app_error', 'FeaturesStore.save',
                null, $e->getMessage(), StatusInternalServerError);
        }

        return $features;
    }

    //update id factory
    public function update(Features $features)
    {
        if(!$features->id || !is_integer($features->id))
        {
            return Error::NewAppError('model.features.is_valid.id.app_error', 'FeaturesStore.update', null,
                "id={$features->id}",StatusBadRequest);
        }

        try {
            if (! Features::find($features->id)) {
                return Error::NewAppError('store.features.update.find.app_error', 'FeaturesStore.update',
                    null, "id=" . $features->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.features.update.find.app_error', 'FeaturesStore.update', null,
                "id=" . $features->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if(!$data =  $features->toInstanceArray())
            {
                return Error::NewAppError('store.features.update.app_error',
                    'FeaturesStore.update', null, 'empty data to update',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.features.update.app_error',
                'FeaturesStore.update', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            Features::where('id', $features->id)->update($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.features.update.updating.app_error', 'FeaturesStore.update', null,
                "id=" . $features->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $features;
    }

}
