<?php

namespace App\Http\Controllers;

use App\App\AuthorizationRepository;
use App\App\CustomerRepository;
use App\Http\Context\Context;
use App\Models\Customer;
use App\Models\Distributor;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Post;
use App\Models\StaticContent;
use App\Models\Translation;
use App\Models\Error;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CustomersController extends Controller
{
    public function index()
    {
        $customers = Customer::with('distributor')->getDynamic();
        $this->output(['data' => $customers], 200);
    }

    public function getAllCustomer()
    {
        $customers = Customer::with('distributor')->getDynamic();
        $this->output(['data' => $customers], 200);
    }

    public function getCustomerByID($customer_id)
    {
        $customer = Customer::with('distributor')->where('id', $customer_id)->first();
        $this->output(['data' => $customer], 200);
    }

    public function getClientSreachCustomers(CustomerRepository $customer)
    {
        $is_admin = isset($_GET['is_admin']) ? $_GET['is_admin'] : null;

        $distributor_id = isset($_GET['distributor_id']) ? $_GET['distributor_id'] : null;

        $name = isset($_GET['name']) ? $_GET['name'] : null;

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

        $data = $customer->getCustomers($limit, $is_admin, $name, $distributor_id);

        $this->output_json(['data' => $data], 200);

    }

    public function changeActiveClient()
    {
        $customer = Customer::where('id', $this->data['id'])->first();

        if ($customer) {

            $customer->active = $this->data['active'];

            $customer->save();

            $this->output($customer, 200);

        }
        else {

            $error = Error::NewAppError('api.CustomerController.changeActiveClient.app_error', '', [], "Permission", StatusUnauthorized);

            response()->json($error, StatusUnauthorized)->send();

            die;
        }


    }

    public function getForm()
    {
        $customer = Customer::with('distributor')->where([['id', $this->data['id']]])->first();

        if ($customer) {
            $this->output(['data' => $customer], 200);
        } else {
            $this->output([MESSAGE => trans('User does not exist')], 404);
        }
    }

    public function detail()
    {
        $customer = Customer::with('distributor')->where('id', $this->data['id'])->first();
        if ($customer) {
            $this->output(['data' => $customer], 200);
        } else {
            $this->output([MESSAGE => trans('User does not exist')], 404);
        }
    }

    public function saveForm()
    {
        if (isset($this->data['id'])) {
            $authorization = new AuthorizationRepository($this->request);
            $curUserId = $this->curUser->id;
            $permission = Permission::PERMISSION_EDIT_MEMBER()->id;
            if (!$authorization->userHasPermissionToDistributor($permission)) {
                $error = Error::NewPermissionError($curUserId, $permission);
                response()->json($error, StatusForbidden)->send();
                die;
            }
        } else {
            $authorization = new AuthorizationRepository($this->request);
            $curUserId = $this->curUser->id;
            $permission = Permission::PERMISSION_CREATE_MEMBER()->id;
            if (!$authorization->userHasPermissionToDistributor($permission)) {
                $error = Error::NewPermissionError($curUserId, $permission);
                response()->json($error, StatusForbidden)->send();
                die;
            }
        }
//        if ($this->data['group'] == GROUP_ADMIN || $this->data['group'] == null) {
        //---------------------validate admin---------------------------
        if (isset($this->data['id'])) {
            // Update

            if ($this->data['username']) {
                unset($this->data['username']);
            }

            if ($this->data['password'] != null || $this->data['password_confirmation'] != null) {

                $this->validate($this->request,
                    [
                        'name' => 'required|min:3|max:255',
                        'email' => 'required|sometimes|nullable|email|unique:customers,email,' . $this->data['id'],
                        'password' => 'no_space|min:8|max:255',
                        'password_confirmation' => 'no_space|min:8|max:255|same:password',
                        'distributor_id' => 'required',
                        'is_admin' => 'required',
                        'telephone' => 'numeric|max:11111111111|unique:customers,telephone,' . $this->data['id'],
                    ],
                    [
                        'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                        'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                        'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                        'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                        'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                        'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                        'password.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password']),
                        'password.min' => trans('messages.api.min.app_error', ['Name' => 'password']),
                        'password.max' => trans('messages.api.max.app_error', ['Name' => 'password']),
                        'password_confirmation.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password confirmation']),
                        'password_confirmation.max' => trans('messages.api.max.app_error', ['Name' => 'password confirmation']),
                        'password_confirmation.min' => trans('messages.api.min.app_error', ['Name' => 'password confirmation']),
                        'password_confirmation.same' => trans('messages.api.same.app_error', ['Name' => 'password_confirmation']),
                        'distributor_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'distributor']),
                        'is_admin.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'role']),
                        'telephone.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                        'telephone.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                        'telephone.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                        'telephone.unique' => trans('messages.api.exist.app_error', ['Name' => 'phone']),
                    ]);

            } else {

                $this->validate($this->request,
                    [
                        'name' => 'required|min:3|max:255',
                        'email' => 'required|sometimes|nullable|email|unique:customers,email,' . $this->data['id'],
                        'distributor_id' => 'required',
                        'is_admin' => 'required',
                        'telephone' => 'numeric|max:11111111111|unique:customers,telephone,' . $this->data['id'],
                    ],
                    [
                        'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                        'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                        'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                        'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                        'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                        'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                        'distributor_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'distributor']),
                        'is_admin.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'role']),
                        'telephone.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                        'telephone.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                        'telephone.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                        'telephone.unique' => trans('messages.api.exist.app_error', ['Name' => 'phone']),
                    ]);

            }

            if ($this->data['password'] && !$this->data['password_confirmation']) {
                $this->output(['password_confirmation' => [trans('The password confirmation field is required.')]], 422);
                die;
            }
            if (!$this->data['password'] && $this->data['password_confirmation']) {
                $this->output(['password' => [trans('The password field is required.')]], 422);
                die;
            }
            if (!empty($this->data['password'])) {
                $this->data['password'] = Hash::make($this->data['password']);
            } else {
                unset($this->data['password']);
            }
        } else {
            // Create new
            $this->validate($this->request,
                [
                    'name' => 'required|min:3|max:255',
                    'password' => 'required|no_space|min:8|max:255',
                    'password_confirmation' => 'required|no_space|min:8|max:255|same:password',
                    'email' => 'required|sometimes|nullable|email|unique:customers,email',
                    'distributor_id' => 'required',
                    'is_admin' => 'required',
                    'telephone' => 'required|numeric|max:11111111111|unique:customers,telephone',
                    'username' => 'required|no_space|min:3|max:255|is_ascii|unique:users,username',
                ],
                [
                    'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                    'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                    'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                    'password.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password']),
                    'password.min' => trans('messages.api.min.app_error', ['Name' => 'password']),
                    'password.max' => trans('messages.api.max.app_error', ['Name' => 'password']),
                    'password_confirmation.min' => trans('messages.api.min.app_error', ['Name' => 'password confirmation']),
                    'password_confirmation.max' => trans('messages.api.max.app_error', ['Name' => 'password confirmation']),
                    'password_confirmation.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password confirmation']),
                    'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                    'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                    'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                    'password_confirmation.same' => trans('messages.api.same.app_error', ['Name' => 'password_confirmation']),
                    'distributor_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'distributor']),
                    'is_admin.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'role']),
                    'telephone.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                    'telephone.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                    'telephone.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                    'telephone.unique' => trans('messages.api.exist.app_error', ['Name' => 'phone']),
                    'username.is_ascii' => trans('messages.api.is_ascii.app_error', ['Name' => 'username']),
                    'username.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'username']),
                    'username.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'username']),
                    'username.unique' => trans('messages.api.exist.app_error', ['Name' => 'username']),
                    'username.min' => trans('messages.api.min.app_error', ['Name' => 'username']),
                    'username.max' => trans('messages.api.max.app_error', ['Name' => 'username']),
                ]);
            if ($this->data['password'] && !$this->data['password_confirmation']) {
                $this->output(['password_confirmation' => [trans('The password confirmation field is required.')]], 422);
                die;
            }
            $this->data['password'] = Hash::make($this->data['password']);
        }
//        }
        $this->saveRecord('Customer', ACTIVE_TRUE);
    }

    public function delete()
    {
        $this->deleteRecord('Customer');
    }

    public function forgotPass(Request $request)
    {
        $this->validate($this->request,
            [
                'email' => 'required|email'
            ],
            [
                'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
            ]);

        $customer = Customer::where(['email' => $this->data['email']])->first();
        if ($customer->active === ACTIVE_FALSE) {
            $this->output([MESSAGE => trans('Account is blocked')], 422);
        }

        if ($customer) {

            // Send verification code
            $verificationCode = mt_rand(1111111111, 9999999999) . $this->data['time_token'] . mt_rand(1111111111, 9999999999);

//            $authCode = sha1($customer->id . $verificationCode);
            $host = $request->getHost();
            $code = $host . '/customer/forgot-change?token=' . $verificationCode;
            $update = ['extra_token' => $verificationCode];
            $customer->fill($update);
            $customer->update();
            if (!empty($customer->email)) {
                // Send email
                Log::info("gui mail");
                dispatch(new SendMail
                (
                    EMAIL_TEMPLATE_FORGOT_PASSWORD,
                    [
                        'to' => $customer->email,
                        'subject' => 'forgotPass'
                    ],
                    [
                        'user' => $customer->name,
                        'code' => $code,
                        'isCustomer' => true,
                        'name1' => 'x',
                    ]
                ));
                $this->output([MESSAGE => 'Check the code'], 200);
            }

        } else {
            $this->output([MESSAGE => trans('Customer does not exist')], 422);
        }
    }


    public function createCustomer(Customer $customer)
    {
        $this->validate($this->request,
            [
                'name' => 'required',
                'store_id' => 'required|integer',
                'type' => 'required',
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'store_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'store_id']),
                'store_id.integer' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'store_id']),
                'type.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'type']),
                'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                'password.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password']),
                'store_id.unique' => trans('messages.api.exist.app_error', ['Name' => 'store']),
            ]);

        $customer = $customer->toModel($this->data);

        $result = $customer->createCustomer();

        $this->output_json($result, 200);
    }

    public function changePass(Customer $customer)
    {
        $this->validate($this->request,
            [
                'extra_token' => 'required',
                'password_new' => 'required|no_space|min:8|max:255|is_ascii',
                'password_confirmation' => 'required|no_space|min:8|max:255|same:password_new|is_ascii'
            ],
            [
                'extra_token.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'extra token']),
                'password_new.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password new']),
                'password_new.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'password new']),
                'password_new.min' => trans('messages.api.min.app_error', ['Name' => 'password new']),
                'password_new.max' => trans('messages.api.max.app_error', ['Name' => 'password new']),
                'password_new.is_ascii' => trans('messages.api.is_ascii.app_error', ['Name' => 'password new']),
                'password_confirmation.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password confirmation']),
                'password_confirmation.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'password confirmation']),
                'password_confirmation.min' => trans('messages.api.min.app_error', ['Name' => 'password confirmation']),
                'password_confirmation.max' => trans('messages.api.max.app_error', ['Name' => 'password confirmation']),
                'password_confirmation.is_ascii' => trans('messages.api.is_ascii.app_error', ['Name' => 'password confirmation']),
                'password_confirmation.same' => trans('messages.api.same.app_error', ['Name' => 'password_confirmation']),
            ]);

        $extra_token = $this->data['extra_token'];

        $password_new = $this->data['password_new'];

        $result = $customer->changePass($extra_token, $password_new);

        $this->output_json_client($result, 200);
    }

    public function changeEmail(Customer $customer) {
        $result = $customer->changeEmail($this->data['email']);
        $this->output_json_client($result, 200);
    }

    public function updateCustomer($customer_id, Customer $customer)
    {
        $this->data['id'] = $customer_id;

        $customer = $customer->toModel($this->data);

        $result = $customer->updateCustomer();

        $this->output_json($result, 200);
    }

    public function deleteCustomer($customer_id, Customer $customer)
    {
        $this->data['id'] = $customer_id;

        $customer = $customer->toModel($this->data);

        $result = $customer->deleteCustomer();

        $this->output_status_ok($result);
    }

    public function getCustomerByName($customer_name, Customer $customer)
    {
        $result = $customer->getCustomerByName($customer_name);

        $this->output_json($result, 200);
    }

    public function getCustomerByStoreId($store_id, Customer $customer)
    {
        $result = $customer->getCustomerByStoreId($store_id);

        $this->output_json($result, 200);
    }

    public function searchCustomersByName(Customer $customer)
    {
        $this->validate($this->request,
            [
                'name' => 'required'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name'])
            ]);

        $result = $customer->searchCustomersByname($this->data['name']);

        $this->output_json($result, 200);
    }

    public function login(CustomerRepository $cr, Context $context)
    {
        $context->setRequest($this->request);

        // require email
        $validate = $context->requireEmailOrUserName();
        if ((is_object($validate)) && get_class($validate) == Error::class) {
            $this->output_json_client($validate, 200);
        }

        // require password
        $validate = $context->requirePassword();
        if ((is_object($validate)) && get_class($validate) == Error::class) {
            $this->output_json_client($validate, 200);
        }

        if ($this->data['emailOrusrname']) {
            $emailORusername = $this->data['emailOrusrname'];
        }
        $password = $this->data['password'];

        $customer = $cr->getCustomerByEmailOrUser($emailORusername);

        if ((is_object($customer)) && get_class($customer) == Error::class) {
            $this->output_json_client($customer, 200); // return error immediate
        }

        $auth = $cr->doLogin($customer, $password);

        $this->output_json_client($auth, 200);
    }

    public function getUser($customer_id, CustomerRepository $cr, Context $context)
    {
        $this->request->merge(['user_id' => $customer_id]);
        $context->setRequest($this->request);
        $validate = $context->requireCustomerId();
        if ((is_object($validate)) && get_class($validate) == Error::class) {
            $this->output_json_client($validate, 200);
        }
        // Check here for permission if have
        $canSee = $cr->customerCanSeeOtherUser($this->request->curSession->customer_id, $this->request->input('user_id'));

        $user = $cr->getCustomer($this->request->input('user_id'));

        if (is_object($user) && get_class($user) == Customer::class) {
            $etag = $user->etag();
        }

        $canEtag = $context->handleEtag($etag);

        if ($canEtag) {
            Header("HTTP/1.1 304 Not Modified");
            $this->output_json_client(['data' => 'success'], 304);
        }
        header(HEADER_ETAG_SERVER . ':' . $etag); //HTTP 1.0
        $this->output_json_client($user, 200);
    }

    public function checkTime($time)
    {
        dd($time);
    }

    public function createStaff(Customer $customer, Request $request)
    {
        $this->validate($this->request,
            [
                'name' => 'required|min:3|max:255',
                'password' => 'required|no_space|min:8|max:255',
                'password_confirmation' => 'required|no_space|min:8|max:255|same:password',
                'email' => 'required|sometimes|nullable|email|unique:customers,email',
                'distributor_id' => 'required',
                'telephone' => 'required|numeric|max:11111111111|unique:customers,telephone',
                'username' => 'required|no_space|min:3|max:255|is_ascii|unique:users,username',
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                'password.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password']),
                'password.min' => trans('messages.api.min.app_error', ['Name' => 'password']),
                'password.max' => trans('messages.api.max.app_error', ['Name' => 'password']),
                'password_confirmation.min' => trans('messages.api.min.app_error', ['Name' => 'password confirmation']),
                'password_confirmation.max' => trans('messages.api.max.app_error', ['Name' => 'password confirmation']),
                'password_confirmation.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password confirmation']),
                'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                'password_confirmation.same' => trans('messages.api.same.app_error', ['Name' => 'password_confirmation']),
                'distributor_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'distributor']),
                'telephone.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                'telephone.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                'telephone.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                'telephone.unique' => trans('messages.api.exist.app_error', ['Name' => 'phone']),
                'username.is_ascii' => trans('messages.api.is_ascii.app_error', ['Name' => 'username']),
                'username.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'username']),
                'username.no_space' => trans('messages.api.no_space.app_error', ['Name' => 'username']),
                'username.unique' => trans('messages.api.exist.app_error', ['Name' => 'username']),
                'username.min' => trans('messages.api.min.app_error', ['Name' => 'username']),
                'username.max' => trans('messages.api.max.app_error', ['Name' => 'username']),
            ]);

        if ((int)$this->data['permission'] === 1) {
            $customer = $customer->toModel($this->data);

            $result = $customer->createStaff();

            $verificationCode = mt_rand(1111111111, 9999999999) . $result . mt_rand(1111111111, 9999999999);

            $host = $request->getHost();
            $code = $host . '/customer/login?active=' . $verificationCode;

            if (!empty($result)) {
                // Send email
                Log::info("gui mail");
                dispatch(new SendMail
                (
                    EMAIL_TEMPLATE_ACCOUNT_ACTIVE,
                    [
                        'to' => $customer->email,
                        'subject' => 'AccountActive'
                    ],
                    [
                        'user' => $customer->name,
                        'code' => $code,
                        'isCustomer' => true,
                        'name1' => 'x',
                    ]
                ));

                $this->output_json_client($result, 200);

            } else {

                $error = Error::NewAppError('api.CustomerController.staffRegister.app_error', '', [], "Permission", StatusUnauthorized);

                response()->json($error, StatusUnauthorized)->send();

                die;
            }
        }
    }

    public function updateStaff($customer_id, Customer $customer, Request $request)
    {
        if ($this->data['password'] != null || $this->data['password_confirmation'] != null) {
            $this->validate($this->request,
                [
                    'name' => 'required|min:3|max:255',
                    'password' => 'no_space|min:8|max:255',
                    'password_confirmation' => 'no_space|min:8|max:255|same:password',
                    'email' => ['required', 'sometimes', 'nullable', Rule::unique('customers')->ignore($customer_id)],
                    'distributor_id' => 'required',
                    'telephone' => 'numeric|max:11111111111|unique:customers,telephone,' . $customer_id,
                ],
                [
                    'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                    'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                    'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                    'password.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password']),
                    'password.min' => trans('messages.api.min.app_error', ['Name' => 'password']),
                    'password.max' => trans('messages.api.max.app_error', ['Name' => 'password']),
                    'password_confirmation.min' => trans('messages.api.min.app_error', ['Name' => 'password confirmation']),
                    'password_confirmation.max' => trans('messages.api.max.app_error', ['Name' => 'password confirmation']),
                    'password_confirmation.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'password confirmation']),
                    'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                    'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                    'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                    'password_confirmation.same' => trans('messages.api.same.app_error', ['Name' => 'password_confirmation']),
                    'distributor_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'distributor']),
                    'telephone.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                    'telephone.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                    'telephone.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                    'telephone.unique' => trans('messages.api.exist.app_error', ['Name' => 'phone']),
                ]);
        } else {
            $this->validate($this->request,
                [
                    'name' => 'required|min:3|max:255',
                    'email' => ['required', 'sometimes', 'nullable', Rule::unique('customers')->ignore($customer_id)],
                    'distributor_id' => 'required',
                    'telephone' => ['required', 'numeric', 'max:11111111111', Rule::unique('customers')->ignore($customer_id)],
                ],
                [
                    'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
                    'name.min' => trans('messages.api.min_name.app_error', ['Name' => 'name']),
                    'name.max' => trans('messages.api.max.app_error', ['Name' => 'name']),
                    'email.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'email']),
                    'email.email' => trans('messages.api.format_email.app_error', ['Name' => 'email']),
                    'email.unique' => trans('messages.api.exist.app_error', ['Name' => 'email']),
                    'distributor_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'distributor']),
                    'telephone.numeric' => trans('messages.api.integer_param.app_error', ['Name' => 'phone']),
                    'telephone.max' => trans('messages.api.phone_max.app_error', ['Name' => 'phone']),
                    'telephone.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'phone']),
                    'telephone.unique' => trans('messages.api.exist.app_error', ['Name' => 'phone']),
                ]);
        }


        if ((int)$this->data['permission'] === 1) {

            $this->data['id'] = $customer_id;

            if (empty($this->data['password'])) {

                unset($this->data['password']);
            }

            $customer = $customer->toModel($this->data);
            $host = $request->getHost();

            $result = $customer->updateStaff($host);

            $this->output_json_client($result, 200);

        } else {

            $error = Error::NewAppError('api.CustomerController.staffRegister.app_error', '', [], "Permission", StatusUnauthorized);

            response()->json($error, StatusUnauthorized)->send();

            die;
        }

    }

}
