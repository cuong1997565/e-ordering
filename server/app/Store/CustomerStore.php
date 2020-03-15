<?php

namespace App\Store;

use App\Jobs\SendMail;
use App\Models\AppModel;
use App\Models\Customer;
use App\Models\Error;
use http\Env\Request;
use  Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerStore
{
    public function get($id)
    {
        if (filter_var($id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.customer.is_valid.id.app_error', 'CustomerStore.get', null, "id={$id}", StatusBadRequest);
        }

        try {
            $customer = Customer::with('distributor')->find($id);
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.get.finding.app_error', 'CustomerStore.get', null, "id=" . $id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        if ($customer == null) {
            return Error::NewAppError('store.customer.get.find.app_error', 'CustomerStore.get', null, "id={$id}", StatusNotFound);
        }

        return $customer;
    }

    public function getCustomerAccountHolder($id) {
        if (filter_var($id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.customer.is_valid.id.app_error', 'CustomerStore.getCustomerAccountHolder', null, "id={$id}", StatusBadRequest);
        }
        try {
            $customer = Customer::where('distributor_id' , $id)->where('is_admin' , IS_ACCOUNT_HOLDER)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.get.finding.app_error', 'CustomerStore.getCustomerAccountHolder', null, "id=" . $id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        if ($customer == null) {
            return Error::NewAppError('store.customer.get.find.app_error', 'CustomerStore.getCustomerAccountHolder', null, "id={$id}", StatusNotFound);
        }

        return $customer;
    }

    public function save(Customer $customer)
    {
        if (filter_var($customer->id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('store.customer.save.existing.app_error', 'CustomerStore.save', null, "id={$customer->id}", StatusBadRequest);
        }

        $customer->id = null;

        try {
            if (!$data = $customer->toInstanceArray()) {
                return Error::NewAppError('store.customer.save.app_error', 'CustomerStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.save.app_error', 'CustomerStore.save', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            $customer = Customer::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.save.app_error', 'CustomerStore.save', null, $e->getMessage(), StatusInternalServerError);
        }

        return $customer;
    }

    public function update(Customer $customer)
    {
        if (filter_var($customer->id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.customer.is_valid.id.app_error', 'CustomerStore.update', null, "id={$customer->id}", StatusBadRequest);
        }

        try {
            if (!Customer::find($customer->id)) {
                return Error::NewAppError('store.customer.update.find.app_error', 'CustomerStore.update', null, "id=" . $customer->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.update.finding.app_error', 'CustomerStore.update', null, "id=" . $customer->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $customer->toInstanceArray()) {
                return Error::NewAppError('store.customer.update.app_error', 'CustomerStore.update', null, 'empty data to update', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.update.app_error', 'CustomerStore.update', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            $customer = Customer::updateOrCreate(['id' => $customer->id], $data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.update.updating.app_error', 'CustomerStore.update', null, "id=" . $customer->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $customer;
    }

    public function changePass($extra_token, $password_new)
    {
        dd(123);
        if (!$extra_token || !is_string($extra_token)) {
            return Error::NewAppError('model.customer.is_valid.id.app_error', 'CustomerStore.update', null, "id={$extra_token}", StatusBadRequest);
        }

        try {
            if (!Customer::where('extra_token', $extra_token)) {
                return Error::NewAppError('store.customer.update.find.app_error', 'CustomerStore.update', null, "id=" . $extra_token, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.update.finding.app_error', 'CustomerStore.update', null, "id=" . $extra_token . $e->getMessage(), StatusInternalServerError);
        }

        try {
            $customer = new Customer();
            $customer = $customer->where('extra_token', $extra_token)->first();
            $extra_token_new = sha1('[' . $customer->id . '-' . date('Y-m-d H:i:s') . ']');
            if ($customer) {
                $customer->password = $password_new;
                $customer->extra_token = $extra_token_new;
                $customer->save();
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.update.updating.app_error', 'CustomerStore.update', null, "id=" . $extra_token . $e->getMessage(), StatusInternalServerError);
        }

        return $customer;
    }

    public function changeEmail($email) {
        $customer = Customer::where('email_change', $email)->first();
        try {
            if (!$email) {
                return Error::NewAppError('store.customer.update.find.app_error', 'CustomerStore.changeEmail', null, "email=" . $email, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.update.finding.app_error', 'CustomerStore.changeEmail', null, "email=" . $email . $e->getMessage(), StatusInternalServerError);
        }

        if($customer == NULL) {
            return Error::NewAppError('store.customer.update.find.app_error', 'CustomerStore.changeEmail', null, "email=" . $email, StatusBadRequest);
        }

        try {
            if( $customer ) {
                $current_datetime = date('Y-m-d H:i');
                $update_date = date("Y-m-d H:i", strtotime($customer['updated_at']));
                if(((strtotime($current_datetime) - strtotime($update_date)) / 60) <= 30) {
                    $customer['email'] = $customer['email_change'];
                    $customer->save();
                } else {
                    return Error::NewAppError('store.customer.time.email', 'CustomerStore.changeEmail', null, "email=" . $email, StatusInternalServerError);

                }
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.update.updating.app_error', 'CustomerStore.changeEmail', null, "email=" . $email . $e->getMessage(), StatusInternalServerError);

        }
        return $customer;
    }

    public function delete(Customer $customer)
    {
        if (filter_var($customer->id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.customer.is_valid.id.app_error', 'CustomerStore.delete', null, "id={$customer->id}", StatusBadRequest);
        }

        try {
            Customer::destroy($customer->id);
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.delete.app_error', 'CustomerStore.save', null, 'id=' . $customer->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return true;
    }

    public function getByName($name)
    {
        if (!is_string($name)) {
            return Error::NewAppError('model.customer.is_valid.name.app_error', 'CustomerStore.getByName', null, "name={$name}", StatusBadRequest);
        }

        try {
            $result = Customer::where('name', $name)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.get_by_name.app_error', 'CustomerStore.getByName', ['Name' => $name], 'name=' . $name . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        if (!$result) {
            return Error::NewAppError('store.customer.get_by_name.first.app_error', 'CustomerStore.getByName', ['Name' => $name], "name={$name}", StatusNotFound);
        }
        return $result;
    }

    public function getByEmailOrUsername($emailORusername)
    {
        if (!is_string($emailORusername)) {
            return Error::NewAppError('model.customer.is_valid.emailORusername.app_error', 'CustomerStore.getByEmailOrUsername', null, "name={$emailORusername}", StatusBadRequest);
        }

        try {
            $result = Customer::where('email', $emailORusername)->orwhere('username', $emailORusername)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.get_by_email_or_username.app_error', 'CustomerStore.getByEmailOrUsername', ['Name' => $emailORusername], 'name=' . $emailORusername . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        if (!$result) {
            return Error::NewAppError('store.customer.get_by_email_or_username.first.app_error', 'CustomerStore.getByEmailOrUsername', ['Name' => $emailORusername], "name={$emailORusername}", StatusNotFound);
        }
        return $result;
    }

    public function getByStoreId($store_id)
    {
        if (filter_var($store_id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.customer.is_valid.store_id.app_error', 'CustomerStore.getByStoreId', null, "store_id={$store_id}", StatusBadRequest);
        }

        try {
            $result = Customer::where('store_id', $store_id)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.get_by_store_id.app_error', 'CustomerStore.getByStoreId', ['StoreId' => $store_id], 'store_id=' . $store_id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        if (!$result) {
            return Error::NewAppError('store.customer.get_by_store_id.first.app_error', 'CustomerStore.getByStoreId', ['StoreId' => $store_id], "store_id={$store_id}", StatusNotFound);
        }
        return $result;
    }

    public function searchByName($name)
    {
        if (!is_string($name)) {
            return Error::NewAppError('model.customer.is_valid.name.app_error', 'CustomerStore.searchByName', null, "name={$name}", StatusBadRequest);
        }
        try {
            $result = Customer::where('name', 'like', $name . '%')->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.search_by_name.app_error', 'CustomerStore.searchByName', ['Name' => $name], 'name=' . $name . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $result;
    }

    public function createStaff($password, $customer)
    {
        if (filter_var($customer->id, FILTER_VALIDATE_INT) === true) {
            return Error::NewAppError('store.customer.createStaff.existing.app_error', 'CustomerStore.createStaff', null, "id={$customer->id}", StatusBadRequest);
        }

        $customer->id = null;

        try {
            if (!$data = $customer->toInstanceArray()) {
                return Error::NewAppError('store.customer.createStaff.app_error', 'CustomerStore.createStaff', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.createStaff.app_error', 'CustomerStore.createStaff', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            $customer = $customer->toArray();
            $customer['password'] = $password;
            $customer = Customer::create($customer)->id;
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.save.app_error', 'CustomerStore.save', null, $e->getMessage(), StatusInternalServerError);
        }

        return $customer;
    }

    public function updateStaff($password, $customer, $host)
    {
        if (filter_var($customer->id, FILTER_VALIDATE_INT) === true) {
            return Error::NewAppError('store.customer.updateStaff.existing.app_error', 'CustomerStore.updateStaff', null, "id={$customer->id}", StatusBadRequest);
        }

        try {
            if (!$data = $customer->toInstanceArray()) {
                return Error::NewAppError('store.customer.updateStaff.app_error', 'CustomerStore.updateStaff', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.updateStaff.app_error', 'CustomerStore.updateStaff', null, 'cannot convert instance to array ', StatusInternalServerError);
        }
        DB::beginTransaction();
        try {
            $data = $customer->toArray();
            $data['email_change'] = $data['email'];
            if($data['email']) {
                unset($data['email']);
            }
            $data['password'] = $password;
            if($data['id']){
                unset($data['id']);
            }

            $customerUpdate = Customer::where('id', $customer->id)->update($data);

            $customer_email = Customer::where('email_change', $data['email_change'])->first();

//            if ($customer_email->active === ACTIVE_FALSE) {
//                $this->output([MESSAGE => trans('Account is blocked')], 422);
//            }

            if ($customer_email) {
                if($customer_email['email'] !== $data['email_change']) {
                    // Send verification code
                    $code = $host . '/customer/change-email?email='. $customer_email->email_change;
                    if (!empty($customer_email->email)) {
                        // Send email
                        Log::info("gui mail");
                        dispatch(new SendMail
                        (
                            EMAIL_TEMPLATE_FORGOT_PASSWORD,
                            [
                                'to' => $customer_email->email_change,
                                'subject' => 'changeEmail'
                            ],
                            [
                                'user' => $customer_email->name,
                                'code' => $code,
                                'isCustomer' => true,
                                'name1' => 'x',
                            ]
                        ));
                    }
                }
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.customer.save.app_error', 'CustomerStore.save', null, $e->getMessage(), StatusInternalServerError);
        }


        return $customer;
    }

    public function getCustomers($limit, $is_admin, $name, $distributor_id)
    {
        if (!is_null($name) && !is_string($name)) {
            return Error::NewAppError('model.customer.is_valid.name.app_error', 'CustomerStore.getCustomers', null, "name={$name}", StatusBadRequest);
        }

        if (!is_null($is_admin) && filter_var($is_admin, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.customer.is_valid.is_admin.app_error', 'CustomerStore.getCustomers', null, "name={$is_admin}", StatusBadRequest);
        }

        if (!is_null($distributor_id) && filter_var($distributor_id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.customer.is_valid.distributor_id.app_error', 'CustomerStore.getCustomers', null, "name={$distributor_id}", StatusBadRequest);
        }

        try {
            $result = new Customer();

            $result = $result->with(['distributor'])->where('distributor_id', $distributor_id);

            if ($is_admin) {
                $result = $result->where('is_admin', $is_admin);
            }

            if ($name) {
                $result = $result->where('name', 'LIKE', "%$name%");
            }

            $result = $result->orderBy('id', 'desc');

            $result = $result->paginate($limit);

            return $result;
        } catch (\Exception $e) {
            return Error::NewAppError('store.customer.search_customer.app_error', 'CustomerStore.getCustomers', null, $e->getMessage(), StatusInternalServerError);

        }
    }
}
