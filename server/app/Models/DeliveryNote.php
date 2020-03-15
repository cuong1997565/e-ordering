<?php
namespace App\Models;
use App\Helpers\Util;
use App\Store\AreaStore;

class DeliveryNote extends AppModel
{
    public static $deliveryDraftStatus = 0;
    public static $deliveryConfirmStatus = 1;
    public static $deliveryReverseStatus = 2;
    public static $deliveryWaitingTransaction = 3;
    public static $deliveryWaitingApproveWhenOver = 4;
    public static $deliveryApproved = 5;
    public static $deliveryReject = 6;
    public static $discountTypeNormal = 1;
    public static $discountTypeStack = 2;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','dn_number', 'distributor_id', 'factory_id','erp_so_number', 'amount', 'amount_after_discount', 'sale_person_id', 'status','notes'
    ];

    public static function mapFieldsFromSaleOrder() {
        // left: sale_orders_table
        // right: delivery_notes_table
        return [
            'distributor_id' => 'distributor_id',
            'factory_id' => 'factory_id',
            'sale_person_id' => 'sale_person_id',
            'amount' => 'amount'
        ];
    }

    public static function fillFromSaleOrder(array $attributes) {
        $dn = new DeliveryNote();
        $mapFields = self::mapFieldsFromSaleOrder();
        foreach ($mapFields as $key => $value) {
            if (isset($attributes[$key]) && $attributes[$key]) {
                $dn->$value = $attributes[$key];
            }
        }
        return $dn;
    }

    public function items() {
        return $this->hasMany(DeliveryNoteItem::class, 'dn_id', 'id');
    }

    public function discountItems() {
        return $this->hasMany(DiscountItem::class, 'delivery_note_id', 'id');
    }
    public function reverse_items() {
        return $this->items()->where('revert_from_item_id', '!=', null);
    }

    public function distributor() {
        return $this->belongsTo(Distributor::class);
    }

    public function factory() {
        return $this->belongsTo(Factory::class,'factory_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'sale_person_id');
    }


    public function isValid()
    {
//        if (!($this->status == WAITING_FOR_CONFIRM_ORDER || $this->status == PROCESSING_ORDER || $this->status == COMPLETED_ORDER ||
//            $this->status == REJECT_BY_SALES_ORDER || $this->status == CANCELLED_BY_CUSTOMER_ORDER || $this->status == SALES_APPROVED_ORDER || $this->status == DELIVERING_ORDER ||
//            $this->status == CUSTOMER_SUBMITED_ORDER)) {
//            return Error::NewAppError('model.order.is_valid.status.app_error', 'Order.isValid', null, "id=".$this->id, StatusBadRequest);
//        }
        if ($this->distributor_id && !Util::validateInteger($this->distributor_id)) {
            return Error::NewAppError('model.delivery_note.is_valid.distributor_id.app_error', 'DeliveryNote.isValid', null, "", StatusBadRequest);
        }
        if ($this->factory_id && !Util::validateInteger($this->factory_id)) {
            return Error::NewAppError('model.delivery_note.is_valid.factory_id.app_error', 'DeliveryNote.isValid', null, "", StatusBadRequest);
        }
        if ($this->sale_person_id && !Util::validateInteger($this->sale_person_id)) {
            return Error::NewAppError('model.delivery_note.is_valid.sale_person_id.app_error', 'DeliveryNote.isValid', null, "", StatusBadRequest);
        }
        if ($this->status && !Util::validateInteger($this->status)) {
            return Error::NewAppError('model.delivery_note.is_valid.status.app_error', 'DeliveryNote.isValid', null, "", StatusBadRequest);
        }

        return true;
    }
}
