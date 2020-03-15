<?php
namespace App\Models;

use App\Store\FactoryStore;
use App\Store\UomStore;

class Uom extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'code',
        'display_name',
        'description',
        'is_based_uom',
        'isrounded',
        'conversion_rate',
        'round_priority',
        'based_uom_id',
        'active'
    ];

    /**
     * $this :
     'name',
    'code',
    'display_name',
    'description',
    'is_based_uom',
    'isrounded',
    'conversion_rate',
    'round_priority',
    'based_uom_id'
     *
     * @var array
     */
    public function createUom() {
        $this->id = null;

        $result = (new UomStore())->save($this);

        return $result;
    }

    /**
     * $this : 'name', 'code', 'display_name','description'
     *
     * @var array
     */
    public function updateUom() {
        $result = (new UomStore())->update($this);

        return $result;
    }

    public function order_products()
    {
        return $this->hasMany(OrderProduct::class, 'uom_id');
    }

    public function sale_order_items()
    {
        return $this->hasMany(SaleOrderItem::class, 'uom_id');
    }

    public function uoms() {
        return $this->hasMany(Uom::class,'based_uom_id');
    }

}
