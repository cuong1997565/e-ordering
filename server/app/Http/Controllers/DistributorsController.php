<?php

namespace App\Http\Controllers;

use App\App\AuthorizationRepository;
use App\Models\CreditAccount;
use App\Models\Distributor;
use App\Models\DistributorProduct;
use App\Models\Error;
use App\Models\Permission;
use App\Providers\Logger\Facade\AppLogger;
use Illuminate\Support\Facades\Log;
use Illuminate\Log\LogManager;

class DistributorsController extends Controller
{
    public function index()
    {
        if (!$this->authorization->userHasPermissionToDistributor(Permission::PERMISSION_LIST_DISTRIBUTORS()->id)) {
            $error = Error::NewPermissionError($this->curUser->id, Permission::PERMISSION_LIST_DISTRIBUTORS()->id);
            $this->output_json($error, 200);
        }

        $distributor = Distributor::with('area', 'products','credit_accounts')->getDynamic();

        $this->output_json(['data' => $distributor], 200);
    }

    public function checkCustomer()
    {
        $distributor = Distributor::where('active', 1)->whereDoesntHave('customers', function ($query) {
            $query->where('is_admin', 1);
        })->get();

        $this->output_json(['data' => $distributor], 200);
    }

    public function checkCreditAccount()
    {
        $distributor = Distributor::where('active', 1)->doesntHave('credit_account')->get();

        $this->output_json(['data' => $distributor], 200);
    }

    public function getDitributorActive()
    {
        if (!$this->authorization->userHasPermissionToDistributor(Permission::PERMISSION_LIST_DISTRIBUTORS()->id)) {
            $error = Error::NewPermissionError($this->curUser->id, Permission::PERMISSION_LIST_DISTRIBUTORS()->id);
            $this->output_json($error, 200);
        }

        $distributor = Distributor::where('active', 1)->getDynamic();

        $this->output_json(['data' => $distributor], 200);
    }

    public function indexClient()
    {

        $distributor = Distributor::with('area', 'products')->getDynamic();

        $this->output_json(['data' => $distributor], 200);
    }

    public function createDistributor(Distributor $distributor, CreditAccount $creditAccount)
    {
        if (!$this->authorization->userHasPermissionToDistributor(Permission::PERMISSION_CREATE_DISTRIBUTOR()->id)) {
            $error = Error::NewPermissionError($this->curUser->id, Permission::PERMISSION_CREATE_DISTRIBUTOR()->id);
            $this->output_json($error, 200);
        }

        $this->validate($this->request,
            [
                'name' => 'required',
                'email' => 'required|email|unique:distributors,email',
                'address' => 'required',
                'contact_person' => 'required',
                'phone' => 'required|numeric|max:11111111111',
                'area_id' => 'required',
                'code' => 'required|no_space|unique:brands,code||is_ascii',
                'tax_code' => 'required|no_space|is_ascii',
                'credit_limit' => 'required|integer|min:1'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'contact_person.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'contact person']),
                'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                'address.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'address']),
                'phone.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                'phone.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                'phone.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                'area_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'province']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'code.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'code']),
                'code.is_ascii' => trans('messages.api.is_ascii.app_error', ['Name' => 'code']),
                'tax_code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'tax code']),
                'tax_code.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'tax code']),
                'tax_code.is_ascii' => trans('messages.api.is_ascii.app_error', ['Name' => 'tax code']),
                'credit_limit.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'credit limit']),
                 'credit_limit.integer' => trans('messages.api.integer_param.app_error',['Name' => 'credit limit']),
                 'credit_limit.min' => trans('messages.api.min.credit_limit.app_error',['Name' => 'credit limit']),

            ]);



        $distributor = $distributor->toModel($this->data);

        $creditAccount = $creditAccount->toModel($this->data);


        $result = $distributor->createDistributor($creditAccount);

        if (is_string($this->data['listProduct'])) {
            $this->data['listProduct'] = json_decode($this->data['listProduct']);
        }

        if (is_array($this->data['listProduct']) && empty($this->data['listProduct'])) {
            unset($this->data['listProduct']);
        } else {
            if (isset($this->data['listProduct'])) {
                foreach ($this->data['listProduct'] as $product) {
                    if (is_object($product) && get_class($product) === \stdClass::class) {
                        $product = json_decode(json_encode($product), true);
                        unset($product['code']);
                        unset($product['image']);
                        unset($product['name']);
                        unset($product['id']);
                        $data = DistributorProduct::create([
                            'distributor_id' => $result['id'],
                            'product_id' => $product['product_id'],
                            'min_quantity' => $product['min_quantity'] ? $product['min_quantity'] : null,
                            'max_quantity' => $product['max_quantity'] ? $product['max_quantity'] : null,
                            'max_hold_age' => $product['max_hold_age'] ? $product['max_hold_age'] : null,
                        ]);
                    }
                }
            }
        }

        $this->output_json($result, 200);
    }

    public function updateDistributor($distributor_id, Distributor $distributor, CreditAccount $creditAccount)
    {
        if (!$this->authorization->userHasPermissionToDistributor(Permission::PERMISSION_EDIT_DISTRIBUTOR()->id)) {
            $error = Error::NewPermissionError($this->curUser->id, Permission::PERMISSION_EDIT_DISTRIBUTOR()->id);
            $this->output_json($error, 200);
        }
        $this->validate($this->request,
            [
                'name' => 'required',
                'email' => 'required|email|unique:distributors,email,' . $distributor_id,
                'address' => 'required',
                'phone' => 'required|numeric|max:11111111111',
                'area_id' => 'required',
                'code' => 'required|no_space|unique:brands,code|is_ascii',
                'contact_person' => 'required',
                'tax_code' => 'required|no_space|is_ascii',
                'credit_limit' => 'required|integer|min:1'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                'address.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'address']),
                'phone.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                'phone.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                'phone.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                'area_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'province']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'code.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'code']),
                'code.is_ascii' => trans('messages.api.is_ascii.app_error', ['Name' => 'code']),
                'tax_code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'tax code']),
                'tax_code.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'tax code']),
                'tax_code.is_ascii' => trans('messages.api.is_ascii.app_error', ['Name' => 'tax code']),
                'contact_person.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'contact person']),
                'credit_limit.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'credit limit']),
                'credit_limit.integer' => trans('messages.api.integer_param.app_error',['Name' => 'credit limit']),
                'credit_limit.min' => trans('messages.api.min.credit_limit.app_error',['Name' => 'credit limit']),
            ]);

        if (is_string($this->data['listProduct'])) {
            $this->data['listProduct'] = json_decode($this->data['listProduct']);
        }

        if (is_array($this->data['listProduct']) && empty($this->data['listProduct'])) {
            unset($this->data['listProduct']);
        } else {
            if (isset($this->data['listProduct'])) {
                foreach ($this->data['listProduct'] as $product) {
                    if (is_object($product) && get_class($product) === \stdClass::class) {
                        $product = json_decode(json_encode($product), true);
                        unset($product['code']);
                        unset($product['image']);
                        unset($product['name']);
                        if ($product['id']) {
                            $update = DistributorProduct::find($product['id']);
                            if ((int)$product['min_quantity']) {
                                $update->min_quantity = $product['min_quantity'];
                            }
                            if ((int)$product['max_quantity']) {
                                $update->max_quantity = $product['max_quantity'];
                            }
                            if ((int)$product['max_hold_age']) {
                                $update->max_hold_age = $product['max_hold_age'];
                            }
                            $update->save();
                        } else {
                            unset($product['id']);
                            DistributorProduct::create([
                                'distributor_id' => $product['distributor_id'],
                                'product_id' => $product['product_id'],
                                'min_quantity' => $product['min_quantity'] ? $product['min_quantity'] : null,
                                'max_quantity' => $product['max_quantity'] ? $product['max_quantity'] : null,
                                'max_hold_age' => $product['max_hold_age'] ? $product['max_hold_age'] : null,
                            ]);
                        }
                    }
                }
            }
        }

        $this->data['id'] = $distributor_id;

        $distributor = $distributor->toModel($this->data);

        $creditAccount = $creditAccount->toModel($this->data);

        $creditAccount['distributor_id'] = $distributor_id;


        $result = $distributor->updateDistributor($creditAccount);

        $this->output_json($result, 200);
    }

    public function searchDistributorsByName(Distributor $distributor)
    {
        $this->validate($this->request,
            [
                'name' => 'required'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name'])
            ]);

        $result = $distributor->searchDistributorsByName($this->data['name']);

        $this->output_json($result, 200);
    }

    public function getDistributorByName($distributor_name, Distributor $distributor)
    {
        $result = $distributor->getDistributorByName($distributor_name);
        return $this->output_json($result, 200);
    }

    public function delete()
    {
        if (!$this->authorization->userHasPermissionToDistributor(Permission::PERMISSION_DELETE_DISTRIBUTOR()->id)) {
            $error = Error::NewPermissionError($this->curUser->id, Permission::PERMISSION_DELETE_DISTRIBUTOR()->id);
            $this->output_json($error, 200);
        }
        $this->deleteRecord('Distributor');
    }

    public function deleteDistributorProduct($distributor_id)
    {
        if (!$this->authorization->userHasPermissionToDistributor(Permission::PERMISSION_REMOVE_PRODUCT_FROM_DISTRIBUTOR()->id)) {
            $error = Error::NewPermissionError($this->curUser->id, Permission::PERMISSION_REMOVE_PRODUCT_FROM_DISTRIBUTOR()->id);
            $this->output_json($error, 200);
        }
        $distributorproduct = DistributorProduct::find($distributor_id);

        $distributorproduct->delete();
    }
}
