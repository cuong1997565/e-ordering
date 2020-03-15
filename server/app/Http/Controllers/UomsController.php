<?php

namespace App\Http\Controllers;

use App\App\FactoryRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\App\UomRepository;
use App\Models\Uom;
class UomsController extends Controller
{

    protected $validationRules = [
        'name'    => 'required',
        'code'    => 'required|unique:uoms,code',
        'display_name' => 'required',
        'description' => 'required',
        'conversion_rate' => 'required|numeric|min:0',
        'round_priority' => 'required|numeric|regex:/(^[A-Za-z0-9 ]+$)+/'
    ];

    public function getAll()
    {
        $result = Uom::getDynamic();
        return $this->output_json(['data' => $result], 200);
    }

    public function getSomeFieldWhereUom($id) {
        $result = Uom::where([['id' , $id],['is_based_uom' , IS_BASED_UOM_FALSE]])
            ->where('active', 1)
            ->orwhere([['based_uom_id' , $id],['is_based_uom' , IS_BASED_UOM_FALSE]])
            ->orwhere([['id' , $id],['is_based_uom' , IS_BASED_UOM_TRUE]])
            ->get();

        return $this->output_json(['data' => $result], 200);
    }

    public function getUomIsBasedUom() {

        if(isset($this->data['id'])) {
            $result = Uom::where('is_based_uom', IS_BASED_UOM_TRUE)
                ->where('id','!=',$this->data['id'])
                ->where('active', ACTIVE_TRUE)
                ->getDynamic();
            return $this->output_json(['data' => $result], 200);
        } else {
            $result = Uom::where('is_based_uom', IS_BASED_UOM_TRUE)
                ->where('active', ACTIVE_TRUE)->getDynamic();
            return $this->output_json(['data' => $result], 200);
        }
    }

    public function createUom(Uom $uom) {
        if(((int)$this->data['is_based_uom'] === 0)) {
            $this->validationRules['based_uom_id'] = 'required';
        }
        $this->validate($this->request,
            $this->validationRules,
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name']),
                'description.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'description']),
                'conversion_rate.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'conversion rate']),
                'conversion_rate.numeric' => trans('messages.api.min.uoms.app_error', ['Name' => 'conversion rate']),
                'conversion_rate.min' => trans('messages.api.numberic.uoms.app_error', ['Name' => 'conversion rate']),
                'round_priority.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'round priority']),
                'round_priority.numeric' => trans('messages.api.min.uoms.app_error', ['Name' => 'round priority']),
                'round_priority.regex' => trans('messages.api.min.uoms.app_error', ['Name' => 'round priority']),
                'based_uom_id.required' =>trans('messages.api.invalid_url_param.app_error', ['Name' => 'based uom']),
            ]);
        $uom = $uom->toModel($this->data);
        $result = $uom->createUom();
        return $this->output_json($result, 200);
    }

    public function updateUom($uom_id, Uom $uom) {
        $this->validate($this->request,
            [
                "name"    => 'required',
                "code"    => 'required|unique:uoms,code,'.$uom_id,
                'display_name' => 'required',
                'description' => 'required',
                'conversion_rate' => 'required|numeric|min:0',
                'round_priority' => 'required|numeric|regex:/(^[A-Za-z0-9 ]+$)+/'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name']),
                'description.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'description']),
                'conversion_rate.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'conversion rate']),
                'conversion_rate.numeric' => trans('messages.api.min.uoms.app_error', ['Name' => 'conversion rate']),
                'conversion_rate.min' => trans('messages.api.numberic.uoms.app_error', ['Name' => 'conversion rate']),
                'round_priority.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'round priority']),
                'round_priority.numeric' => trans('messages.api.min.uoms.app_error', ['Name' => 'round priority']),
                'round_priority.regex' => trans('messages.api.min.uoms.app_error', ['Name' => 'round priority'])
            ]);

        $this->data['id'] = $uom_id;

        $uom = $uom->toModel($this->data);

        $result = $uom->updateUom();

        return $this->output_json($result, 200);
    }
}
