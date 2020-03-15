<?php

namespace App\Http\Controllers;
use App\Models\Store;
use App\Models\Distributor;
use Illuminate\Support\Facades\DB;

class StoresController extends Controller
{
   public function getAll()
   {
       $query = Store::with('factory')->getDynamic();

       $this->output(['data'=>$query],200);
   }

   public function getSomeFieldStore() {
       $query = Store::select('id','name','code')->getDynamic();

       $this->output(['data'=>$query],200);

   }

   public function  createStore(Store $store)
   {
       $this->validate($this->request,
           [
               'code'    => 'required|unique:stores,code|regex:/^\S*$/u|regex:/^[a-zA-Z0-9?=.*!@#$%^&*_()\-\s]+$/u',
               'name' => 'required|max:255',
               'factory_id' => 'required'
           ],
           [
               'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
               'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
               'code.regex' => trans('messages.api.regex.code.stores.app_error', ['Name' => 'code']),
               'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
               'name.max' => trans('messages.api.max.product.app_error', ['Name' => 'name', 'Value' => '255']),
               'factory_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'factory'])
           ]);

       $store = $store->toModel($this->data);

       $result = $store->createStore();

       $this->output_json($result, 200);
   }


   public  function getDistributorByName($ditributor_name, Distributor $distributor, Store $store)
   {
       $result = $distributor->getDistributorByName($ditributor_name);

       if($result != null)
       {
          $result = $store->getStoreByDistributorName($result->id);
       }

       return $result;
   }

   public function updateStore($store_id, Store $store)
   {
       //'unique:stores,dimi_email,\'. $store_id
       $this->validate($this->request,
           [
               'code'    => 'required|unique:stores,code,'. $store_id .'|regex:/^\S*$/u|regex:/^[a-zA-Z0-9?=.*!@#$%^&*_()\-\s]+$/u',
               'name' => 'required|max:255',
               'factory_id' => 'required'
           ],
           [
               'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
               'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
               'code.regex' => trans('messages.api.regex.code.stores.app_error', ['Name' => 'code']),
               'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
               'name.max' => trans('messages.api.max.product.app_error', ['Name' => 'name', 'Value' => '255']),
               'factory_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'factory'])
           ]);

        $this->data['id'] = $store_id;

        $store = $store->toModel($this->data);

        $result = $store->updateStore();

        $this->output_json($result, 200);

   }

   public function delete()
   {
       $this->deleteRecord('Store');
   }
}