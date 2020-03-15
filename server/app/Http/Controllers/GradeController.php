<?php

namespace App\Http\Controllers;

use App\App\FactoryRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App\Models\Grade;

class GradeController extends Controller
{
    public function getAll()
    {
        $result = Grade::with(['gradegroup'])->whereHas('gradegroup', function ($query) {
        $query->where('active' , ACTIVE_TRUE);
    })->getDynamic();
        return $this->output_json(['data' => $result], 200);
    }


    public function getSomeFieldGrade()
    {
        $result = Grade::select('id','name','code','display_name')->whereHas('gradegroup', function ($query) {
            $query->where('active' , ACTIVE_TRUE);
        })->get();
        return $this->output_json(['data' => $result], 200);
    }

    public function whereGradeGroup($id) {
        $result = Grade::select('id','name','code','display_name')->where('grade_group_id', $id)->get();
        return $this->output_json(['data' => $result], 200);
    }

    public function getIdGradeGroupAboutGrade($id) {
        $result = Grade::where('grade_group_id', $id)
            ->where('active', 1)
            ->select('id','name','code', 'active')
            ->get();

        return $this->output_json(['data' => $result], 200);
    }

    public function createGrade(Grade $grade)
    {
        $this->validate($this->request,
            [
                'grade_group_id' => 'required',
                'name'    => 'required',
                'code'    => 'required|unique:grades,code',
                'display_name' => 'required'
            ],
            [
                'grade_group_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'grade groups']),
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name'])
            ]);

        $grade = $grade->toModel($this->data);
        $result = $grade->createGrade();
        return $this->output_json($result, 200);
    }

    public function updateGrade($id, Grade $grade) {
        $this->validate($this->request,
            [
                'grade_group_id' => 'required',
                'name'    => 'required',
                'code'    => 'required|unique:grades,code,'.$id,
                'display_name' => 'required'
            ],
            [
                'grade_group_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'grade groups']),
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name'])
            ]);

        $this->data['id'] = $id;

        $grade = $grade->toModel($this->data);

        $result = $grade->updateGrade();

        return $this->output_json($result, 200);
    }

}
