<?php

namespace App\Http\Controllers;

use App\App\FactoryRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App\Models\Features;

class FeaturesController extends Controller
{
    public function getAll()
    {
        $result = Features::getDynamic();
        return $this->output_json(['data' => $result], 200);
    }

    public function createFeatures(Features $features)
    {
        $this->validate($this->request,
            [
                'name'    => 'required',
                'display_name' => 'required'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name'])
            ]);

        $features = $features->toModel($this->data);
        $result = $features->createFeatures();
        return $this->output_json($result, 200);
    }

    public function updateFeatures($id, Features $features) {
        $this->validate($this->request,
            [
                'name'    => 'required',
                'display_name' => 'required'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name'])
            ]);

        $this->data['id'] = $id;

        $features = $features->toModel($this->data);

        $result = $features->updateFeatures();

        return $this->output_json($result, 200);
    }

}
