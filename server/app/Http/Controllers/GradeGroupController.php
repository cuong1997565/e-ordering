<?php

namespace App\Http\Controllers;

use App\App\FactoryRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App\Models\GradeGroup;

class GradeGroupController extends Controller
{
    public function getAll()
    {
        $result = GradeGroup::getDynamic();
        return $this->output_json(['data' => $result], 200);
    }

    /*
     * get some field grade group
     * */
    public function getGradeGroupAboutProduct()
    {
        $result = GradeGroup::select('id','name','code')->getDynamic();
        return $this->output_json(['data' => $result], 200);
    }

    public function createGradeGroup(GradeGroup $gradeGroup)
    {
        $this->validate($this->request,
            [
                'name'    => 'required',
                'code'    => 'required|unique:grade_groups,code'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
            ]);

            $gradeGroup = $gradeGroup->toModel($this->data);
            $result = $gradeGroup->createGradeGroup();
            return $this->output_json($result, 200);
    }

    public function updateGradeGroup($id, GradeGroup $gradeGroup) {
        $this->validate($this->request,
            [
                "name"    => 'required',
                "code"    => 'required|unique:grade_groups,code,'.$id,

            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code'])
            ]);

        $this->data['id'] = $id;

        $gradeGroup = $gradeGroup->toModel($this->data);

        $result = $gradeGroup->updateGradeGroup();

        return $this->output_json($result, 200);
    }

}
