<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\AttributeListsOfValue;

class AttributeListsOfValueController extends Controller
{
    public function getAll()
    {
        $result = AttributeListsOfValue::getDynamic();
        return $this->output_json(['data' => $result], 200);
    }

    public function createAttributeListsOfValue(AttributeListsOfValue $attributeListsOfValue)
    {
        $this->validate($this->request,
            [
                'attribute_id'    => 'required',
                'value' => 'required'
            ],
            [
                'attribute_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'attribute']),
                'value.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'value'])
            ]);

        $attributeListsOfValue = $attributeListsOfValue->toModel($this->data);

        $result = $attributeListsOfValue->createAttributeListsOfValue();

        return $this->output_json($result, 200);

    }

    public function updateAttributeListsOfValue($id, AttributeListsOfValue $attributeListsOfValue) {
        $this->validate($this->request,
            [
                'attribute_id'    => 'required',
                'value' => 'required'
            ],
            [
                'attribute_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'attribute']),
                'value.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'value'])
            ]);

        $this->data['id'] = $id;

        $attributeListsOfValue = $attributeListsOfValue->toModel($this->data);

        $result = $attributeListsOfValue->updateAttributeListsOfValue();

        return $this->output_json($result, 200);
    }

    public function getSomeAttributeList() {
        $item  = [];
        $array = json_decode($this->data['product_attr']);
        foreach ($array as $key => $value) {
            if($value->type == Attributes_Type_List) {
                $data = AttributeListsOfValue::where('attribute_id', $value->id)
                    ->where('active', 1)
                    ->get();
                array_push($item, $data);
            }
        }

        return $this->output_json(['data' => $item], 200);
    }
}
