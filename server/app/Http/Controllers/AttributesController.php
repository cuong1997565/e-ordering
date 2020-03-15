<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Attributes;

class AttributesController extends Controller
{
    protected $validationRules = [
        'name'    => 'required',
        'type'    => 'required',
        'code'    => 'required|unique:attributes,code',
        'product_type_id' => 'required',
        'description' => 'required',
        'sequence' => 'required|integer'
    ];


    public function getAll()
    {
        $result = Attributes::with('producttype')->getDynamic();
        return $this->output_json(['data' => $result], 200);
    }

    public function getSomeFieldWhereAttribute($id)
    {
        $result = Attributes::select('id','code','name')
            ->with([
                'attributelist' => function ($q) {
                    $q->where('attribute_lists_of_values.active', 1);
                }
            ])
            ->where('attributes.active', 1)
            ->where('attributes.product_type_id', $id)->getDynamic();

        return $this->output_json(['data' => $result], 200);
    }

    public function createAttributes(Attributes $attributes)
    {
        $this->validate(
            $this->request,
            $this->validationRules,
            [
                'type.required'=> trans('messages.api.invalid_url_param.app_error', ['Name' => 'type']),
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'product_type_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'product type']),
                'description.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'description']),
                'sequence.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'sequence']),
                'sequence.integer' => trans('messages.api.integer.price.app_error', ['Name' => 'sequence']),

            ]
        );

        $attributes = $attributes->toModel($this->data);
        $result = $attributes->createAttributes();

        return $this->output_json($result, 200);
    }

    public function updateAttributes($id, Attributes $attributes) {
        $this->validationRules['code'] .=','.$id;

        $this->validate($this->request,
            $this->validationRules,
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'product_type_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'product type']),
                'description.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'description']),
                'sequence.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'sequence']),
                'sequence.integer' => trans('messages.api.integer.price.app_error', ['Name' => 'sequence'])
            ]);

        $this->data['id'] = $id;

        $attributes = $attributes->toModel($this->data);

        $result = $attributes->updateAttributes();

        return $this->output_json($result, 200);
    }
}
