<?php

namespace App\Http\Controllers;

use App\App\FactoryRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App\Models\ProductType;

class ProductTypeController extends Controller
{
    public function getAll()
    {
        $result = ProductType::getDynamic();
        return $this->output_json(['data' => $result], 200);
    }
    /*
     * get all field table product type
     * */
    public function getProductTypeAboutProduct() {
        $result = ProductType::select('id','code','name')->getDynamic();
        return $this->output_json(['data' => $result], 200);
    }
    public function createProductType(ProductType $productType) {
        $this->validate($this->request,
            [
                'name'    => 'required',
                'code'    => 'required|unique:product_types,code',
                'description' => 'required'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name']),
                'description.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'description'])
            ]);

        $productType = $productType->toModel($this->data);
        $result = $productType->createProductType();
        return $this->output_json($result, 200);
    }

    public function updateProductType($id, ProductType $productType) {
        $this->validate($this->request,
            [
                "name"    => 'required',
                "code"    => 'required|unique:product_types,code,'.$id,
                'description' => 'required'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'description.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'description'])
            ]);

        $this->data['id'] = $id;

        $productType = $productType->toModel($this->data);

        $result = $productType->updateProductType();

        return $this->output_json($result, 200);
    }
}
