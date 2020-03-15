<?php
namespace App\Models;
use App\Store\PriceListItemStore;

class PriceListItem extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'price_list_id',
        'product_id',
        'grade_id',
        'unit_price'
    ];

    public function grade() {
        return $this->belongsTo(Grade::class);
    }

    public function price_list() {
        return $this->belongsTo(PriceList::class);
    }

    public function createPriceListItem($priceList = null) {
        $this->id = null;

        $result = (new PriceListItemStore())->save($priceList);

        return $result;
    }
}
