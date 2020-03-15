<?php
namespace App\Models;
use App\Helpers\Util;
use App\Store\AreaStore;

class DiscountItem extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','discount_type_id', 'delivery_note_id', 'discount_rate','discount_amount'
    ];

}
