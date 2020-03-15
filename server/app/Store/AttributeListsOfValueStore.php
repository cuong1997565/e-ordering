<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\AttributeListsOfValue;
use App\Models\Attributes;
use App\Models\Error;

class AttributeListsOfValueStore
{
    public function save(AttributeListsOfValue $attributeListsOfValue) {
        if ($attributeListsOfValue->id !== null && $attributeListsOfValue->id !== 0)
        {
            return Error::NewAppError('store.attribute.list.of.value.save.existing.app_error',
                'AttributeListsOfValueStore.save', null, "id={$attributeListsOfValue->id}",StatusBadRequest);
        }

        $attributeListsOfValue->id = null;

        /*
         * find id attribute where
         * */
        $data = Attributes::where('id' , $attributeListsOfValue->attribute_id)->where('type' , Attributes_Type_List)->get();

        try {
            if (count($data) < 1) {
                return Error::NewAppError('store.stores.attributes.find.app_error', 'AttributeListsOfValueStore.update',
                    null, "id=" . $attributeListsOfValue->attribute_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.attributes.find.app_error', 'AttributeListsOfValueStore.update', null,
                "id=" . $attributeListsOfValue->attribute_id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try
        {
            if(! $data = $attributeListsOfValue->toInstanceArray())
            {
                return Error::NewAppError('store.attribute.list.of.value.save.app_error', 'AttributeListsOfValueStore.save', null,
                    'empty data to insert',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.attribute.list.of.value.save.app_error', 'AttributeListsOfValueStore.save', null,
                'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            $attributeListsOfValue = AttributeListsOfValue::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.attribute.list.of.value.save.app_error', 'AttributeListsOfValueStore.save',
                null, $e->getMessage(), StatusInternalServerError);
        }

        return $attributeListsOfValue;
    }

    //update attribute list of value
    public function update(AttributeListsOfValue $attributeListsOfValue)
    {
        if(!$attributeListsOfValue->id || !is_integer($attributeListsOfValue->id))
        {
            return Error::NewAppError('model.attribute.list.of.value.is_valid.id.app_error', 'AttributeListsOfValueStore.update', null,
                "id={$attributeListsOfValue->id}",StatusBadRequest);
        }

        /*
        * find id attribute where
        * */
        $data = Attributes::where('id' , $attributeListsOfValue->attribute_id)->where('type' , Attributes_Type_List)->get();

        try {
            if (count($data) < 1) {
                return Error::NewAppError('store.stores.attributes.find.app_error', 'AttributeListsOfValueStore.update',
                    null, "id=" . $attributeListsOfValue->attribute_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.attributes.find.app_error', 'AttributeListsOfValueStore.update', null,
                "id=" . $attributeListsOfValue->attribute_id . ', ' . $e->getMessage(), StatusInternalServerError);
        }


        try {
            if (! AttributeListsOfValue::find($attributeListsOfValue->id)) {
                return Error::NewAppError('store.attribute.list.of.value.update.find.app_error', 'AttributeListsOfValueStore.update',
                    null, "id=" . $attributeListsOfValue->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.attribute.list.of.value.update.find.app_error', 'AttributeListsOfValueStore.update', null,
                "id=" . $attributeListsOfValue->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if(!$data =  $attributeListsOfValue->toInstanceArray())
            {
                return Error::NewAppError('store.attribute.list.of.value.update.app_error',
                    'AttributeListsOfValueStore.update', null, 'empty data to update',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.attribute.list.of.value.update.app_error',
                'AttributeListsOfValueStore.update', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            AttributeListsOfValue::where('id', $attributeListsOfValue->id)->update($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.attribute.list.of.value.update.updating.app_error', 'AttributeListsOfValueStore.update', null,
                "id=" . $attributeListsOfValue->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $attributeListsOfValue;
    }

}
