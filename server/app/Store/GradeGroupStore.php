<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\GradeGroup;
use App\Models\Uom;
use App\Models\Error;

class GradeGroupStore
{
    public function save(GradeGroup $gradeGroup) {
        if ($gradeGroup->id !== null && $gradeGroup->id !== 0)
        {
            return Error::NewAppError('store.grade.groups.save.existing.app_error',
                'GradeGroupStore.save', null, "id={$gradeGroup->id}",StatusBadRequest);
        }

        $gradeGroup->id = null;

        try
        {
            if(! $data = $gradeGroup->toInstanceArray())
            {
                return Error::NewAppError('store.grade.groups.save.app_error', 'GradeGroupStore.save', null,
                    'empty data to insert',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.grade.groups.save.app_error', 'GradeGroupStore.save', null,
                'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            $gradeGroup = GradeGroup::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.grade.groups.save.app_error', 'GradeGroupStore.save',
                null, $e->getMessage(), StatusInternalServerError);
        }

        return $gradeGroup;
    }

    //update id GradeGroup
    public function update(GradeGroup $gradeGroup)
    {
        if(!$gradeGroup->id || !is_integer($gradeGroup->id))
        {
            return Error::NewAppError('model.grade.groups.is_valid.id.app_error', 'GradeGroupStore.update', null,
                "id={$gradeGroup->id}",StatusBadRequest);
        }

        try {
            if (! GradeGroup::find($gradeGroup->id)) {
                return Error::NewAppError('store.grade.groups.update.find.app_error', 'GradeGroupStore.update',
                    null, "id=" . $gradeGroup->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.grade.groups.update.find.app_error', 'GradeGroupStore.update', null,
                "id=" . $gradeGroup->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if(!$data =  $gradeGroup->toInstanceArray())
            {
                return Error::NewAppError('store.grade.groups.update.app_error',
                    'GradeGroupStore.update', null, 'empty data to update',StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.grade.groups.update.app_error',
                'GradeGroupStore.update', null, 'cannot convert instance to array ',StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            GradeGroup::where('id', $gradeGroup->id)->update($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.grade.groups.update.updating.app_error', 'GradeGroupStore.update', null,
                "id=" . $gradeGroup->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $gradeGroup;
    }

}
