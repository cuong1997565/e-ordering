<?php
namespace App\Models;
use App\Store\Stores;
class Store extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name','code','factory_id', 'active'
    ];

    public $belongTo =
        [
            'factory',
        ];

    public function factory() {
        return $this->belongsTo(Factory::class,'factory_id');
    }


    public function createStore()
    {
        $this->id = null;

        $result = (new Stores())->save($this);

        return $result;
    }

    public function updateStore()
    {
       $result = (new Stores())->update($this);

       return $result;
    }

    public function getStoreByDistributorName($distributor_id)
    {
        $storeOrError = (new Stores())->getStoreByDistributorId($distributor_id);

        if ((is_object($storeOrError)) && get_class($storeOrError) == Error::class) {
            $storeOrError->StatusCode = StatusNotFound;
        }

        return $storeOrError;
    }
}
