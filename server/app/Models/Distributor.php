<?php

namespace App\Models;

use App\Store\DistributorStore;

class Distributor extends AppModel
{
    protected $fillable = [
        'id',
        'name',
        'email',
        'address',
        'phone',
        'area_id',
        'code',
        'tax_code',
        'contact_person',
        'active'
    ];

    /*
     * create distributor
     * $this : ['name','email','address','phone','area_id','code','tax_code','contact_person','active']
     * $creditAccount : ['distributor_id', 'amount', 'hold_amount', 'available_amount', 'credit_limit']
     * */

    public function createDistributor($creditAccount = null)
    {
        $this->id = null;

        $result = (new DistributorStore())->save($this, $creditAccount);

        return $result;
    }

    /*
    * update distributor
    * $this : ['name','email','address','phone','area_id','code','tax_code','contact_person','active']
    * $creditAccount : ['distributor_id', 'amount', 'hold_amount', 'available_amount', 'credit_limit']
    * */

    public function updateDistributor($creditAccount)
    {
        $result = (new DistributorStore())->update($this, $creditAccount);

        return $result;
    }

    public function searchDistributorsByName($name)
    {
        $distributorOrError = (new DistributorStore())->searchByName($name);

        return $distributorOrError;

    }

    public function getDistributorByName($name)
    {
        $distributorOrError = (new DistributorStore())->getByName($name);

        if ((is_object($distributorOrError)) && get_class($distributorOrError) == Error::class) {

            $distributorOrError->StatusCode = StatusNotFound;

        }

        return $distributorOrError;
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'distributor_product', 'distributor_id', 'product_id')->withPivot('id','product_id','distributor_id', 'min_quantity', 'max_quantity', 'max_hold_age');
    }

    public function credit_accounts()
    {
        return $this->hasOne(CreditAccount::class, 'distributor_id');
    }

    public function credit_account()
    {
        return $this->hasOne(CreditAccount::class, 'distributor_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'distributor_id');
    }
}
