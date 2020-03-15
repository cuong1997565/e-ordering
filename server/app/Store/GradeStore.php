<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\Grade;
use App\Models\Error;
use App\Models\GradeGroup;

class GradeStore
{
    public function save(Grade $grade) {
        if ($grade->id !== null && $grade->id !== 0)
        {
            return Error::NewAppError('store.grade.save.existing.app_error',
                'GradeStore.save', null, "id={$grade->id}",StatusBadRequest);
        }

        $grade->id = null;

        try {
            if (!GradeGroup::find($grade->grade_group_id)) {
                return Error::NewAppError('store.stores.grade.groups.find.app_error', 'GradeStore.save',
                    null, "id=" . $grade->uom_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.grade.groups.find.app_error', 'GradeStore.save',
                null, "id=" . $grade->uom_id. $e->getMessage(), StatusInternalServerError);
        }

        try
        {
            if(! $data = $grade->toInstanceArray())
            {
                return Error::NewAppError('store.grade.save.app_error', 'GradeStore.save', null,
                    'empty data to insert',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.grade.save.app_error', 'GradeStore.save', null,
                'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            $grade = Grade::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.grade.save.app_error', 'GradeStore.save',
                null, $e->getMessage(), StatusInternalServerError);
        }

        return $grade;
    }

    //update id GradeGroup
    public function update(Grade $grade)
    {
        if(!$grade->id || !is_integer($grade->id))
        {
            return Error::NewAppError('model.grade.is_valid.id.app_error', 'GradeStore.update', null,
                "id={$grade->id}",StatusBadRequest);
        }

        try {
            if (!GradeGroup::find($grade->grade_group_id)) {
                return Error::NewAppError('store.stores.grade.groups.find.app_error', 'GradeStore.save',
                    null, "id=" . $grade->uom_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.grade.groups.find.app_error', 'GradeStore.save',
                null, "id=" . $grade->uom_id. $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (! Grade::find($grade->id)) {
                return Error::NewAppError('store.grade.update.find.app_error', 'GradeStore.update',
                    null, "id=" . $grade->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.grade.update.find.app_error', 'GradeStore.update', null,
                "id=" . $grade->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if(!$data =  $grade->toInstanceArray())
            {
                return Error::NewAppError('store.grade.update.app_error',
                    'GradeStore.update', null, 'empty data to update',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.grade.update.app_error',
                'GradeStore.update', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            Grade::where('id', $grade->id)->update($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.grade.update.updating.app_error', 'GradeStore.update', null,
                "id=" . $grade->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $grade;
    }

}
