<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\PriceList;

class PriceListController extends Controller
{
    public function getAll()
    {
        $result = PriceList::with('price_list_items')->orderBy('is_default', 'DESC')->getDynamic();
        return $this->output_json(['data' => $result], 200);
    }

    public function getSomeFieldPriceList() {
        $result = PriceList::select('name','code','id')->getDynamic();
        return $this->output_json(['data' => $result], 200);
    }

    public function index($price_list_id)
    {
        $result = PriceList::with('price_list_items')->where('id', $price_list_id)->get();

        return $this->output_json(['data' => $result], 200);
    }

    public function createPriceList(PriceList $priceList) {
        $this->validate($this->request,
            [
                'name'    => 'required',
                'code'    => 'required|unique:price_lists,code',
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),

            ]);

        $priceList = $priceList->toModel($this->data);

        $result = $priceList->createPriceList();

        return $this->output_json($result, 200);
    }

    public function updatePriceList($id, PriceList $priceList) {
        $this->validate($this->request,
            [
                'name'    => 'required',
                "code"    => 'required|unique:price_lists,code,'.$id
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
            ]);
        $this->data['id'] = $id;

        $priceList = $priceList->toModel($this->data);

        $result = $priceList->updatePriceList();

        return $this->output_json($result, 200);
    }
}
