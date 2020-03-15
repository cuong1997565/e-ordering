<?php

namespace App\Http\Controllers;

use App\Models\Specification;

class SpecificationsController extends Controller
{
    public function index()
    {
        $specification = Specification::getDynamic();

        $this->output_json(['data' => $specification], 200);
    }

    //get specification  products
    public function getSpecificationProduct() {
        $specification = Specification::with('specifications')->getDynamic();
        $this->output_json(['data' => $specification], 200);
    }

    public function createSpecification(Specification $specification)
    {
        $this->validate($this->request,
            [
                'name' => 'required',
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'name']),
            ]);
        $specification = $specification->toModel($this->data);

        $result = $specification->createSpecification();

        $this->output_json($result, 200);
    }

    public function updateSpecification($specification_id, Specification $specification)
    {
        $this->validate($this->request,
            [
                'name' => 'required',
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'name']),
            ]);

        $this->data['id'] = $specification_id;

        $specification = $specification->toModel($this->data);

        $result = $specification->updateSpecification();

        $this->output_json($result, 200);
    }

    public function delete()
    {
        $this->deleteRecord('Specification');
    }
}
