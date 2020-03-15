<?php

namespace App\App;

use App\Models\Customer;
use App\Models\Session;
use App\Models\Error;
use App\Store\CustomerStore;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Support\Facades\Hash;

class CustomerRepository
{
    public function doLogin(Customer $customer, $password)
    {
        if ($customer->active === ACTIVE_FALSE) {
            $error = Error::NewAppError('model.customer.do_login.not_active', 'Customer.doLogin', [], '', StatusBadRequest);
            return $error;
        }

        if (!Hash::check($password, $customer->password)) {
            $error = Error::NewAppError('model.customer.do_login.wrong_password', 'Customer.doLogin', [], '', StatusBadRequest);
            return $error;
        }

        $result = (new SessionRepository())->createSession($customer->id);

        if (is_object($result) && get_class($result) == Session::class) {
            $result->profile = $customer;
        }

        return $result;
    }

    public function getCustomer($customerId)
    {
        return (new CustomerStore())->get($customerId);
    }

    public function getCustomerAccountHolder($id) {

        return (new CustomerStore())->getCustomerAccountHolder($id);
    }

    public function getCustomerByEmailOrUser($emailORusername)
    {
        $customerOrError = (new CustomerStore())->getByEmailOrUsername($emailORusername);

        if ((is_object($customerOrError)) && get_class($customerOrError) == Error::class) {
            $customerOrError->StatusCode = StatusNotFound;
        }

        return $customerOrError;
    }

    public function customerCanSeeOtherUser($customerId, $otherUserId)
    {
        if ($customerId == $otherUserId) {
            return true;
        } else {
            return false;
        }
    }

    public function changePass($extra_token, $password_new)
    {

        $password_new = Hash::make($password_new);

        $result = (new CustomerStore())->changePass($extra_token, $password_new);

        return $result;
    }

    public function changeEmail($email) {
        $result = (new CustomerStore())->changeEmail($email);

        return $result;
    }

    public function createStaff($customer)
    {
        $password = Hash::make($customer->password);

        $result = (new CustomerStore())->createStaff($password, $customer);

        return $result;

    }

    public function updateStaff($customer, $host)
    {
        if ($customer->password) {
            $password = Hash::make($customer->password);

        } else {
            $customer1 = Customer::where('id', $customer->id)->first();
            $password = $customer1->password;
        }
        $result = (new CustomerStore())->updateStaff($password, $customer, $host);

        return $result;

    }

    public function getCustomers($limit, $is_admin, $name, $distributor_id)
    {
        $result = (new CustomerStore())->getCustomers($limit, $is_admin, $name, $distributor_id);
        return $result;
    }
}
