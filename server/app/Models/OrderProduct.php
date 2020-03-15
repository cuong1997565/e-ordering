<?php
namespace App\Models;

use App\Helpers\Util;

class OrderProduct extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'id', 'product_id','order_id', 'amount', 'code', 'sale_note',
        'user_note', 'factory_id', 'product_attributes', 'uom_id', 'factory_id',
        'grade_id', 'sale_quantity', 'delivery_quantity', 'remaining_quantity', 'distributor_id',
        'sale_uom','order_quantity','status','code_stock_order_product'

    ];

    public function isValid() {
        if (!Util::validateInteger($this->product_id)) {
            return Error::NewAppError('model.order_product.is_valid.product_id.app_error', 'OrderProduct.isValid', null, "", StatusBadRequest);
        }
        if (!Util::validateInteger($this->order_id)) {
            return Error::NewAppError('model.order_product.is_valid.order_id.app_error', 'OrderProduct.isValid', null, "", StatusBadRequest);
        }
        if (!Util::validateInteger($this->uom_id)) {
            return Error::NewAppError('model.order_product.is_valid.uom_id.app_error', 'OrderProduct.isValid', null, "", StatusBadRequest);
        }
        if (!Util::validateInteger($this->factory_id)) {
            return Error::NewAppError('model.order_product.is_valid.factory_id.app_error', 'OrderProduct.isValid', null, "", StatusBadRequest);
        }
        if (!Util::validateInteger($this->grade_id)) {
            return Error::NewAppError('model.order_product.is_valid.grade_id.app_error', 'OrderProduct.isValid', null, "", StatusBadRequest);
        }
        if (!Util::validateInteger($this->distributor_id)) {
            return Error::NewAppError('model.order_product.is_valid.distributor_id.app_error', 'OrderProduct.isValid', null, "", StatusBadRequest);
        }

        return true;
    }

    public function grade() {
        return $this->belongsTo(Grade::class);
    }

    public function uom() {
        return $this->belongsTo(Uom::class,'sale_uom');
    }

    public function uom_front_end() {
        return $this->belongsTo(Uom::class,'uom_id');

    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
