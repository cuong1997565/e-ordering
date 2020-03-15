<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Providers\Logger\Facade\AppLogger;
use Illuminate\Support\Facades\Log;
use Illuminate\Log\LogManager;
class BrandsController extends Controller
{
    public function index()
    {
        $brand = Brand::getDynamic();

        $this->output_json(['data' => $brand], 200);
    }

    ## Api Branch Product
    public function brandProduct() {
        $brand = Brand::where('active', 1)->getDynamic();
        $this->output_json(['data' => $brand], 200);
    }

    ## Client API Brand
    public function getClientBrands()
    {
        $brand = Brand::where('active', 1)->orderBy('name','asc')->select('id','name')->getDynamic();
        $this->output_json(['data' => $brand], 200);
    }

    public function createBrand(Brand $brand)
    {
        $this->validate($this->request,
            [
                'name' => 'required',
                'code' => 'required|no_space|unique:brands,code'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error',['Name' => 'code']),
                'code.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'code']),
            ]);

        $brand = $brand->toModel($this->data);

        $result = $brand->createBrand();

        $this->output_json($result, 200);
    }

    public function updateBrand($brand_id, Brand $brand)
    {
        $this->validate($this->request,
            [
                'name' => 'required',
                'code' => 'required|no_space|unique:brands,code,'. $brand_id
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error',['Name' => 'code']),
                'code.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'code']),
            ]);

        $this->data['id'] = $brand_id;

        $brand = $brand->toModel($this->data);

        $result = $brand->updateBrand();

        $this->output_json($result, 200);
    }

    public function searchBrandsByName(Brand $brand)
    {
        $this->validate($this->request,
            [
                'name' => 'required'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'name'])
            ]);

        $result = $brand->searchBrandsByName($this->data['name']);

        $this->output_json($result,200);
    }

    public function getBrandByName($brand_name, Brand $brand)
    {
        $result = $brand->getBrandByName($brand_name);
        return $this->output_json($result, 200);
    }

    public function delete()
    {
        $this->deleteRecord('Brand');
    }
}
