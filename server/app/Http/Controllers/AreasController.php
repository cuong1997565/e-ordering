<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Area;

class AreasController extends Controller
{
    public function index()
    {
        $area = Area::getDynamic();

        $this->output_json(['data' => $area], 200);
    }

    public function createArea(Area $area)
    {
        $this->validate($this->request,
            [
                'name' => 'required',
                'code' => 'required|no_space|unique:areas,code|is_ascii',
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error',['Name' => 'code']),
                'code.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'code']),
                'code.is_ascii' => trans('messages.api.is_ascii.app_error', ['Name' => 'code']),
            ]);

        $area = $area->toModel($this->data);

        $result = $area->createArea();

        $this->output_json($result, 200);
    }

    public function updateArea($area_id, Area $area)
    {
        $this->validate($this->request, [
            'id' => 'required|integer',
            'name' => 'required',
            'code' => 'required|no_space|unique:areas,code|is_ascii',
        ],
        [
            'id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'id']),
            'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
            'code.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'code']),
            'code.unique' => trans('messages.api.exist.app_error',['Name' => 'code']),
            'code.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'code']),
            'code.is_ascii' => trans('messages.api.is_ascii.app_error', ['Name' => 'code']),
        ]);

        $this->data['id'] = $area_id;

        $area = $area->toModel($this->data);

        $result = $area->updateArea();

        $this->output_json($result, 200);
    }

    public function getAreaByName($area_name, Area $area)
    {
       $result = $area->getAreaByName($area_name);
       return $this->output_json($result, 200);
    }
  
    public  function searchAreasByName(Area $area)
    {
        $this->validate($this->request,
            [
                'name' => 'required'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name'])
            ]);
        $result = $area->searchAreaByname($this->data['name']);
        return $this->output_json($result, 200);
    }
}
