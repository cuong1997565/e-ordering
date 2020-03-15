<?php
namespace App\Models;
use App\Helpers\Util;
use App\Store\AreaStore;
use App\Store\DiscountTypeStore;

class DiscountType extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','code', 'name', 'display_name', 'is_percentage', 'is_custom_rate', 'is_stack_discount', 'discount_rate'
    ];

    /*
     * create discount type
     * $this :   'id','code', 'name', 'display_name', 'is_percentage', 'is_custom_rate', 'is_stack_discount', 'discount_rate'
     * */
    public function createDiscountType() {
        $this->id = null;

        $result = (new DiscountTypeStore())->save($this);

        return $result;
    }

    /*
        * update discount type
        * $this :   'id','code', 'name', 'display_name', 'is_percentage', 'is_custom_rate', 'is_stack_discount', 'discount_rate'
     * */
    public function updateDiscountType() {
        $result = (new DiscountTypeStore())->update($this);

        return $result;
    }
}
