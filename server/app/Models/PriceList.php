<?php
namespace App\Models;
use App\Store\PriceListStore;

class PriceList extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'code',
        'name',
        'is_default',
        'active'
    ];
    /**
     * $this :
    'code',
    'name',
    'is_default',
    'active'
     *
     * @var array
     */
    public function createPriceList() {
        $this->id = null;

        $result = (new PriceListStore())->save($this);

        return $result;
    }

    /**
     * $this :
    'code',
    'name',
    'is_default',
    'active'
     *
     * @var array
     */

    public function updatePriceList () {
        $result = (new PriceListStore())->update($this);

        return $result;
    }

    public function sale_orders()
    {
        return $this->hasMany(SaleOrder::class,'price_list_id');
    }

    public function price_list_items()
    {
        return $this->hasMany(PriceListItem::class, 'price_list_id');
    }
}
