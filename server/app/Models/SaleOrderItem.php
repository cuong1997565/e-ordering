<?php

namespace App\Models;

use App\Helpers\Util;
use App\Store\OrderStore;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class SaleOrderItem extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sale_order_id',
        'product_id',
        'grade_id',
        'attributes',
        'uom_id',
        'sale_quantity',
        'product_attributes',
        'delivered_quantity',
        'remaining_quantity',
        'unit_price_id',
        'unit_price',
        'amount',
        'status',
        'sale_note',
        'customer_quantity',
        'user_note',
        'code_stock_order_product'
    ];

    public $belongTo =
        [
            'factory',
            'customer'
        ];


    public function saleOrderItemFromOrderProduct(Collection $orderProducts)
    {
        foreach ($orderProducts as $key => $orderProduct) {
            $error = $orderProduct->isValid();
            $order = (new Order());
            $unit_price = $order->getDefaultPrice($orderProduct->product_id);
            foreach ($unit_price[0]->price_list_items as $u) {
                $orderProducts[$key]->unit_price = $u->unit_price;
                $orderProducts[$key]->unit_price_id = $u->price_list_id;
            }
            if (is_object($error) && get_class($error) == Error::class) {
                return $error;
            }
        }

        $saleOrderItems = null;
        if (is_object($orderProducts) && get_class($orderProducts) == Collection::class) {
            $saleOrderItems = $orderProducts->map(function ($item) {

                    $saleOrderItem = new SaleOrderItem();
                    $saleOrderItem->product_id = $item->product_id;
                    $saleOrderItem->grade_id = $item->grade_id;
                    $saleOrderItem->product_attributes = $item->product_attributes;
                    $saleOrderItem->uom_id = $item->sale_uom;
                    $saleOrderItem->sale_quantity = $item->order_quantity;
                    $saleOrderItem->delivered_quantity = null;
                    $saleOrderItem->remaining_quantity = $item->order_quantity - $saleOrderItem->delivered_quantity;
                    $saleOrderItem->unit_price_id = $item->unit_price_id;
                    $saleOrderItem->unit_price = $item->unit_price;
                    $saleOrderItem->amount = null;
                    $saleOrderItem->user_note = $item->user_note;
                    $saleOrderItem->sale_note = $item->sale_note;
                    $saleOrderItem->customer_quantity = $item->order_quantity;
                    $saleOrderItem->code_stock_order_product = $item->code_stock_order_product;
                    return $saleOrderItem;
            });
        }
        return $saleOrderItems;
    }

    public function isValid()
    {
//        if (!($this->status == WAITING_FOR_CONFIRM_ORDER || $this->status == PROCESSING_ORDER || $this->status == COMPLETED_ORDER ||
//            $this->status == REJECT_BY_SALES_ORDER || $this->status == CANCELLED_BY_CUSTOMER_ORDER || $this->status == SALES_APPROVED_ORDER || $this->status == DELIVERING_ORDER ||
//            $this->status == CUSTOMER_SUBMITED_ORDER)) {
//            return Error::NewAppError('model.order.is_valid.status.app_error', 'Order.isValid', null, "id=".$this->id, StatusBadRequest);
//        }
        if ($this->sale_order_id && !Util::validateInteger($this->sale_order_id)) {
            return Error::NewAppError('model.sale_order_item.is_valid.order_id.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }
        if (!Util::validateInteger($this->grade_id)) {
            return Error::NewAppError('model.sale_order_item.is_valid.grade_id.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }
        if (!Util::validateInteger($this->uom_id)) {
            return Error::NewAppError('model.sale_order_item.is_valid.uom_id.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }

        return true;
    }

    public function grade() {
        return $this->belongsTo(Grade::class);
    }

    public function uom() {
        return $this->belongsTo(Uom::class);
    }

    public function sale_order() {
        return $this->belongsTo(SaleOrder::class, 'sale_order_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
