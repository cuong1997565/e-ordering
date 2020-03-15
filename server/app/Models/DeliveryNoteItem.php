<?php
namespace App\Models;
use App\Helpers\Util;
use App\Store\AreaStore;

class DeliveryNoteItem extends AppModel
{
    public static $discountCustomType = 2;
    public static $discountPercentType = 1;
    public static $mapFieldsFromSaleOrderItem = [
        'product_id' => 'product_id',
        'grade_id' => 'grade_id',
        'uom_id' => 'uom_id',
        'unit_price_id' => 'unit_price_id',
        'unit_price' => 'unit_price',
        'amount' => 'amount',
        'product_attributes' => 'product_attributes'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','so_item_id', 'dn_id', 'store_id','product_id', 'grade_id', 'product_attributes', 'uom_id', 'deliver_quantity', 'unit_price', 'unit_price_id', 'amount',
        'discount', 'discount_type', 'amount_after_discount', 'notes', 'revert_from_item_id', 'so_id'
    ];

//    public static function mapFieldsFromFillData() {
//        $array = [];
//        foreach ((new DeliveryNoteItem())->fillable as $field) {
//            if ($field == 'status' || )
//            $array[$field] = $field;
//        }
//        return [
//            'store_id' => 'store_id',
//            'deliver_quantity' => 'deliver_quantity',
//            'distributor_id' => 'distributor_id',
//            'factory_id' => 'factory_id',
//            ''
//        ];
//    }
    public function mapFieldsFromSaleOrderItem() {
        // left: sale_order_items_table
        // right: delivery_note_items_table
        return [
            'product_id' => 'product_id',
            'grade_id' => 'grade_id',
            'uom_id' => 'uom_id',
            'unit_price_id' => 'unit_price_id',
            'unit_price' => 'unit_price',
            'amount' => 'amount',
            'product_attributes' => 'product_attributes'
        ];
    }

    public function delivery_note() {
        return $this->belongsTo(DeliveryNote::class, 'dn_id', 'id');
    }
    public function reverse_items() {
        return $this->hasMany(DeliveryNoteItem::class, 'revert_from_item_id','id');
    }
    public static function getAmount($quantity, $priceUnit, $discount = null, $discountType = null) {
        return $quantity * $priceUnit;
    }
    public static function getAmountAfterDiscount($amount, $discount = null, $discountType = null) {
        if ($discountType == self::$discountCustomType) {
            return $amount - $discount;
        }
        if ($discountType == self::$discountPercentType) {
            return $amount - $amount * $discount;
        }
        return $amount;
    }

    public function isValid()
    {
//        if (!($this->status == WAITING_FOR_CONFIRM_ORDER || $this->status == PROCESSING_ORDER || $this->status == COMPLETED_ORDER ||
//            $this->status == REJECT_BY_SALES_ORDER || $this->status == CANCELLED_BY_CUSTOMER_ORDER || $this->status == SALES_APPROVED_ORDER || $this->status == DELIVERING_ORDER ||
//            $this->status == CUSTOMER_SUBMITED_ORDER)) {
//            return Error::NewAppError('model.order.is_valid.status.app_error', 'Order.isValid', null, "id=".$this->id, StatusBadRequest);
//        }
        if ($this->so_item_id && !Util::validateInteger($this->so_item_id)) {
            return Error::NewAppError('model.delivery_note_item.is_valid.so_item_id.app_error', 'DeliveryNoteItem.isValid', null, "", StatusBadRequest);
        }
        if ($this->dn_id && !Util::validateInteger($this->dn_id)) {
            return Error::NewAppError('model.delivery_note_item.is_valid.dn_id.app_error', 'DeliveryNoteItem.isValid', null, "", StatusBadRequest);
        }
        if ($this->store_id && !Util::validateInteger($this->store_id)) {
            return Error::NewAppError('model.delivery_note_item.is_valid.store_id.app_error', 'DeliveryNoteItem.isValid', null, "", StatusBadRequest);
        }
        if ($this->product_id && !Util::validateInteger($this->product_id)) {
            return Error::NewAppError('model.delivery_note_item.is_valid.product_id.app_error', 'DeliveryNoteItem.isValid', null, "", StatusBadRequest);
        }
        if ($this->grade_id && !Util::validateInteger($this->grade_id)) {
            return Error::NewAppError('model.delivery_note_item.is_valid.grade_id.app_error', 'DeliveryNoteItem.isValid', null, "", StatusBadRequest);
        }
        if ($this->uom_id && !Util::validateInteger($this->uom_id)) {
            return Error::NewAppError('model.delivery_note_item.is_valid.uom_id.app_error', 'DeliveryNoteItem.isValid', null, "", StatusBadRequest);
        }

        return true;
    }


}
