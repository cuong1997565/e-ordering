<?php

namespace App\Models;

use App\App\CustomerRepository;
use App\App\ProductRepository;
use App\App\SessionRepository;
use App\Helpers\Util;
use App\Models\Session;
use App\Store\CustomerStore;
use App\Store\SessionStore;
use http\Env\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Exception;

class Customer extends AppModel
{
    const ME = 'me';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'distributor_id', 'type', 'email', 'password','active','extra_token','is_admin','telephone','username','email_change'
    ];

    protected $hidden = ['password',];

    public function isValidUserId($user_id) {
        return filter_var($user_id, FILTER_VALIDATE_INT) !== false;
    }

    public function getCustomer($teamId)
    {
        return (new CustomerStore())->get($teamId);
    }

    public function getAuthToken()
    {
        return $this->auth_token;
    }

    public function createCustomer()
    {
        $this->id = null;

        $result = (new CustomerStore())->save($this);

        return $result;
    }

    public function createStaff()
    {
        $this->id = null;

        $result = (new CustomerRepository())->createStaff($this);

        return $result;
    }

    public function updateStaff($host)
    {
        $result = (new CustomerRepository())->updateStaff($this, $host);

        return $result;
    }

    public function changePass($extra_token, $password_new)
    {
        $result = (new CustomerRepository())->changePass($extra_token, $password_new);

        return $result;
    }

    public function changeEmail($email) {
        $result = (new CustomerRepository())->changeEmail($email);

        return $result;
    }

    public function updateCustomer()
    {
        $result = (new CustomerStore())->update($this);

        return $result;
    }

    public function deleteCustomer()
    {
        $customerOrError = $this->getCustomer($this->id);

        if ((is_object($customerOrError)) && get_class($customerOrError) == Error::class) {
            return $customerOrError;
        }

        $result = (new CustomerStore())->delete($this);

        return $result;
    }

    public function getCustomerByName($name)
    {
        $customerOrError = (new CustomerStore())->getByName($name);

        if ((is_object($customerOrError)) && get_class($customerOrError) == Error::class) {
            $customerOrError->StatusCode = StatusNotFound;
        }

        return $customerOrError;
    }

    public function getCustomerByStoreId($store_id)
    {
        $customerOrError = (new CustomerStore())->getByStoreId($store_id);

        if ((is_object($customerOrError)) && get_class($customerOrError) == Error::class) {
            $customerOrError->StatusCode = StatusNotFound;
        }

        return $customerOrError;
    }

    public function searchCustomersByname($name)
    {
        $customerOrError = (new CustomerStore())->searchByName($name);

        return $customerOrError;
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function etag() {
        return Util::Etag($this->id, strtotime($this->updated_at));
    }

    public function session()
    {
        return $this->hasMany(Session::class);
    }

    protected static function boot()
    {
        parent::boot();
        self::updated(function (Customer $customer) {
            $oldStatus = $customer->getOriginal('active');
            $newStatus = $customer->active;
            if ((bool) $newStatus != $oldStatus && $newStatus == false) {
                $result = (new SessionRepository())->revokeAllByCustomerId($customer->id);
                if ((is_object($result) && get_class($result) === Error::class)) {
                    throw new Exception('Error when delete session');
                }
                return true;
            }
        });
    }
}
