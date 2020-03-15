<?php

namespace App\Http\Controllers;

use App\App\FactoryRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App\Models\FeatureItem;

class FeatureItemsController extends Controller
{
    public function getAll()
    {
        $result = FeatureItem::with(['feature'])->getDynamic();
        return $this->output_json(['data' => $result], 200);
    }

    /*
     * get all Feature Item
     * */
    public function getFeatureItemAboutProduct() {
        $result = FeatureItem::select('id','name')->where('active', ACTIVE_TRUE)->get();
        return $this->output_json(['data' => $result], 200);
    }

    public function createFeatureItems(FeatureItem $featureItem)
    {
        $this->validate($this->request,
            [
                'feature_id' => 'required',
                'name'    => 'required',
                'display_name' => 'required'
            ],
            [
                'feature_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'features']),
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name'])
            ]);

        $featureItem = $featureItem->toModel($this->data);
        $result = $featureItem->createFeatureItem();
        return $this->output_json($result, 200);
    }

    public function updateFeatureItems($id, FeatureItem $featureItem) {
        $this->validate($this->request,
            [
                'feature_id' => 'required',
                'name'    => 'required',
                'display_name' => 'required'
            ],
            [
                'feature_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'features']),
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name'])
            ]);

        $this->data['id'] = $id;

        $featureItem = $featureItem->toModel($this->data);

        $result = $featureItem->updateFeatureItems();

        return $this->output_json($result, 200);
    }

}
