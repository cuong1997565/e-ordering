<?php

namespace App\Http\Controllers;

use App\App\FactoryRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\App\UomRepository;
use App\Models\UomMultiple;

class UomMultipleController extends Controller
{
    public function getAll()
    {
        $result = UomMultiple::with(['uom' => function($q) {
            $q->select('id','name');
        }])->getDynamic();
        return $this->output_json(['data' => $result], 200);
    }

    public function createUomMultiples(UomMultiple $uomMultiple) {
        $this->validate($this->request,
            [
                'uom_id' =>  'required',
                'name'    => 'required',
                'code'    => 'required|unique:uom_multiples,code',
                'display_name' => 'required',
                'description' => 'required',
                'conversion_rate' => 'required|numeric|min:0',
                'round_priority' => 'required|numeric|regex:/(^[A-Za-z0-9 ]+$)+/'
            ],
            [
                'uom_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'uom']),
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name']),
                'description.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'description']),
                'conversion_rate.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'conversion rate']),
                'conversion_rate.numeric' => trans('messages.api.min.uom.multiple.app_error', ['Name' => 'conversion rate']),
                'conversion_rate.min' => trans('messages.api.numberic.uom.multiple.app_error', ['Name' => 'conversion rate']),
                'round_priority.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'round priority']),
                'round_priority.numeric' => trans('messages.api.min.uom.multiple.app_error', ['Name' => 'round priority']),
                'round_priority.regex' => trans('messages.api.min.uom.multiple.app_error', ['Name' => 'round priority'])
            ]);
        $uomMultiple = $uomMultiple->toModel($this->data);
        $result = $uomMultiple->createUomMultiples();
        return $this->output_json($result, 200);
    }

    public function updateUomMultiples($uom_multiples_id, UomMultiple $uomMultiple) {
        $this->validate($this->request,
            [
                'uom_id' =>  'required',
                'name'    => 'required',
                "code"    => 'required|unique:uom_multiples,code,'.$uom_multiples_id,
                'display_name' => 'required',
                'description' => 'required',
                'conversion_rate' => 'required|numeric|min:0',
                'round_priority' => 'required|numeric|regex:/(^[A-Za-z0-9 ]+$)+/'
            ],
            [
                'uom_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'uom']),
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name']),
                'description.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'description']),
                'conversion_rate.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'conversion rate']),
                'conversion_rate.numeric' => trans('messages.api.min.uom.multiple.app_error', ['Name' => 'conversion rate']),
                'conversion_rate.min' => trans('messages.api.numberic.uom.multiple.app_error', ['Name' => 'conversion rate']),
                'round_priority.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'round priority']),
                'round_priority.numeric' => trans('messages.api.min.uom.multiple.app_error', ['Name' => 'round priority']),
                'round_priority.regex' => trans('messages.api.min.uom.multiple.app_error', ['Name' => 'round priority'])
            ]);
        $this->data['id'] = $uom_multiples_id;

        $uomMultiple = $uomMultiple->toModel($this->data);

        $result = $uomMultiple->updateUomMultiple();

        return $this->output_json($result, 200);
    }
}
