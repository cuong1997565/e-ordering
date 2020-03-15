<?php

namespace App\Http\Controllers;

use App\App\FactoryRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Factory;

class FactoriesController extends Controller
{
    public function getAll(Factory $factories)
    {
        $result = $factories->getAll();
        return $this->output_json(['data' => $result], 200);
    }

    public function getClientFactory()
    {
        $factory = Factory::getDynamic();
        $this->output_json(['data' => $factory], 200);
    }

    public function getClientFactories(FactoryRepository $fr) {
        $result = $fr->getAllWithFields();

        return $this->output_json(['data' => $result], 200);
    }

    public function createFactory(Factory $factories)
    {
        $this->validate($this->request,
            [
                "name"    => 'required',
                "code"    => 'required|unique:factories,code|regex:/^\S*$/u|regex:/^[a-zA-Z0-9?=.*!@#$%^&*_()\-\s]+$/u',
//                "email"   => 'email|unique:factories,email',
//                "phone"   => 'min:10|regex:/^([0-9\s\-\+\(\)]*)$/',
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'code.regex' => trans('messages.api.regex.code.factory.app_error', ['Name' => 'code']),
//                'email.email' => trans('messages.api.format_email.app_error',['Name' => 'email']),
//                'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
//                'phone.min' => trans('messages.api.min.phone.app_error',['Name' => 'Phone']),
//                'phone.regex' => trans('messages.api.number.phone.app_error',['Name' => 'Phone']),
            ]);

        $factories = $factories->toModel($this->data);
        $result = $factories->createFactory();
        return $this->output_json($result, 200);
    }

    public function updateFactory($factory_id, Factory $factories)
    {
        $this->validate($this->request,
            [
                "name"    => 'required',
                "code"    => 'required|regex:/^\S*$/u|regex:/^[a-zA-Z0-9?=.*!@#$%^&*_()\-\s]+$/|unique:factories,code,'.$factory_id,
//                "email"   => 'required|email|unique:factories,email,'.$factory_id,
//                "phone"   => 'required|min:10|regex:/^([0-9\s\-\+\(\)]*)$/',
//                "address" => 'required'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'code.regex' => trans('messages.api.regex.code.factory.app_error', ['Name' => 'code']),
//                'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
//                'email.email' => trans('messages.api.format_email.app_error',['Name' => 'email']),
//                'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
//                'phone.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
//                'phone.min' => trans('messages.api.min.phone.app_error',['Name' => 'Phone']),
//                'phone.regex' => trans('messages.api.number.phone.app_error',['Name' => 'Phone']),
//                'address.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'address'])
            ]);

        $this->data['id'] = $factory_id;

        $factories = $factories->toModel($this->data);

        $result = $factories->updateFactory();

        return $this->output_json($result, 200);

    }

    public function delete()
    {
        $this->deleteRecord('Factory');
    }
}
