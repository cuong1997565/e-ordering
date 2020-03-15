<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\FeatureItem;
use App\Models\Features;
use App\Models\Error;

class FeatureItemStore
{
    public function save(FeatureItem $featureItem) {
        if ($featureItem->id !== null && $featureItem->id !== 0)
        {
            return Error::NewAppError('store.feature.items.save.existing.app_error',
                'FeatureItemStore.save', null, "id={$featureItem->id}",StatusBadRequest);
        }

        $featureItem->id = null;

        try {
            if (! Features::find($featureItem->feature_id)) {
                return Error::NewAppError('store.stores.features.find.app_error', 'FeatureItemStore.save',
                    null, "id=" . $featureItem->feature_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.features.find.app_error', 'FeatureItemStore.save', null,
                "id=" . $featureItem->feature_id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try
        {
            if(! $data = $featureItem->toInstanceArray())
            {
                return Error::NewAppError('store.feature.items.save.app_error', 'FeatureItemStore.save', null,
                    'empty data to insert',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.feature.items.save.app_error', 'FeatureItemStore.save', null,
                'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            $featureItem = FeatureItem::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.feature.items.save.app_error', 'FeatureItemStore.save',
                null, $e->getMessage(), StatusInternalServerError);
        }

        return $featureItem;
    }

    //update id feature item
    public function update(FeatureItem $featureItem)
    {
        if(!$featureItem->id || !is_integer($featureItem->id))
        {
            return Error::NewAppError('model.feature.items.is_valid.id.app_error', 'FeatureItemStore.update', null,
                "id={$featureItem->id}",StatusBadRequest);
        }

        try {
            if (! FeatureItem::find($featureItem->id)) {
                return Error::NewAppError('store.feature.items.update.find.app_error', 'FeatureItemStore.update',
                    null, "id=" . $featureItem->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.feature.items.update.find.app_error', 'FeatureItemStore.update', null,
                "id=" . $featureItem->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (! Features::find($featureItem->feature_id)) {
                return Error::NewAppError('store.stores.features.find.app_error', 'FeatureItemStore.save',
                    null, "id=" . $featureItem->feature_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.features.find.app_error', 'FeatureItemStore.save', null,
                "id=" . $featureItem->feature_id . ', ' . $e->getMessage(), StatusInternalServerError);
        }


        try {
            if(!$data =  $featureItem->toInstanceArray())
            {
                return Error::NewAppError('store.feature.items.update.app_error',
                    'FeatureItemStore.update', null, 'empty data to update',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.feature.items.update.app_error',
                'FeatureItemStore.update', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            FeatureItem::where('id', $featureItem->id)->update($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.feature.items.update.updating.app_error', 'FeatureItemStore.update', null,
                "id=" . $featureItem->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $featureItem;
    }

}
