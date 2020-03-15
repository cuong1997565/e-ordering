<?php

namespace App\Http\Controllers;

use App\App\FactoryRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\DiscountType;

class DiscountTypeController extends Controller
{
    public function getAll(DiscountType $discountType)
    {
        $result = DiscountType::getDynamic();
        return $this->output_json(['data' => $result], 200);
    }

    public function createDiscountType(DiscountType $discountType) {
        $this->validate($this->request,
            [
                'name'    => 'required',
                'code'    => 'required|unique:discount_types,code',
                'display_name' => 'required',
                'discount_rate' => 'required|numeric|min:0.1|max:0.9'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name']),
                'discount_rate.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'discount rate']),
                'discount_rate.numeric' => trans('messages.api.numberic.discount.type.app_error', ['Name' => 'discount rate']),
                'discount_rate.min' => trans('messages.api.min.discount.type.app_error', ['Name' => 'discount rate','Number' => '0.1']),
                'discount_rate.max' => trans('messages.api.max.discount.type.app_error', ['Name' => 'discount rate','Number' => '0.9'])
            ]);

        $discountType = $discountType->toModel($this->data);
        $result = $discountType->createDiscountType();
        return $this->output_json($result, 200);
    }

    public function updateDiscountType($id, DiscountType $discountType) {
        $this->validate($this->request,
            [
                'name'    => 'required',
                'code'    => 'required|unique:discount_types,code,'. $id,
                'display_name' => 'required',
                'discount_rate' => 'required|numeric|min:0.1|max:0.9'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name']),
                'discount_rate.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'discount rate']),
                'discount_rate.numeric' => trans('messages.api.numberic.discount.type.app_error', ['Name' => 'discount rate']),
                'discount_rate.min' => trans('messages.api.min.discount.type.app_error', ['Name' => 'discount rate','Number' => '0.1']),
                'discount_rate.max' => trans('messages.api.max.discount.type.app_error', ['Name' => 'discount rate','Number' => '0.9'])
            ]);

        $this->data['id'] = $id;

        $discountType = $discountType->toModel($this->data);

        $result = $discountType->updateDiscountType();

        return $this->output_json($result, 200);
    }

}
