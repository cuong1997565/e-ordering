<?php

namespace App\Models;
use App\Helpers\Util;
use App\Store\OrderStore;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends AppModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'code',
        'factory_id',
        'creator_id',
        'status',
        'creator_note',
        'deliver_date',
        'deliver_actual',
        'note',
        'total',
        'canceled_date',
        'approved_date',
        'rejected_date',
        'completed_date',
        'processing_date',
        'confirm_date',
        'distributor_id',
        'type',
        'deliver_address',
        'po_number',
        'note_status_close'
    ];

    public $belongTo =
        [
            'factory',
            'customer'
        ];
    protected $dates = ['deleted_at'];

    protected static function boot() {
        parent::boot();

        static::deleted(function ($product_item) {
            $product_item->items()->delete();
        });
    }


    public function factory() {
        return $this->belongsTo(Factory::class,'factory_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class,'creator_id');
    }

    public function items() {
        return $this->hasMany(OrderProduct::class);
    }

    public function distributor() {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id')
            ->withPivot('order_id','amount', 'code', 'sale_note', 'user_note','id',
                'factory_id','deleted_at','grade_id','uom_id','sale_quantity','delivery_quantity','remaining_quantity','product_attributes');
    }

    public function createOrder($products = null) {
        $this->id = null;

        $result = (new OrderStore())->save($this, $products);

        return $result;
    }

    public function updateOrder($product = null) {
        $result = (new OrderStore())->updateOrder($this, $product);

        return $result;
    }

    public function getDefaultPrice($product_id) {
        $result = (new OrderStore())->getDefaultPrice($product_id);

        return $result;
    }

    public function updateOrderClient($product = null) {
        $result = (new OrderStore())->updateOrderClient($this, $product);

        return $result;
    }

    public function updateDrafOrder($product = null) {
        $result = (new OrderStore())->updateDrafOrder($this, $product);

        return $result;
    }

    public function updateStatusOrder() {
        /*
         * $this : id, status, canceled_date truyền từ controller vào
         * */
        $result = (new OrderStore())->updateStatus($this);
        return $result;
    }

    public function isValid() {
        $validateNullFields = ['created_at', 'updated_at'];

        foreach ($validateNullFields as $field) {
            if ($this->{$field} == null) {
                return Error::NewAppError("model.order.is_valid.{$field}.app_error", 'Order.isValid', null, "", StatusBadRequest);
            }
        }

        if (!($this->status == WAITING_FOR_CONFIRM_ORDER || $this->status == REVIEWING_ORDER || $this->status == CLOSED_ORDER ||
                $this->status == REJECT_BY_SALES_ORDER || $this->status == CANCELLED_BY_CUSTOMER_ORDER || $this->status == SALES_ACCEPTED_ORDER || $this->status == DELIVERING_ORDER ||
                $this->status == SUBMITED_ORDER)) {
            return Error::NewAppError('model.order.is_valid.status.app_error', 'Order.isValid', null, "id=".$this->id, StatusBadRequest);
        }

        if (!Util::validateInteger($this->factory_id)) {
            return Error::NewAppError('model.order.is_valid.factory_id.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }
        if (!Util::validateInteger($this->distributor_id)) {
            return Error::NewAppError('model.order.is_valid.distributor_id.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }

        $validateDateFields = ['cancel_date', 'approved_date', 'rejected_date', 'completed_date', 'processing_date', 'confirm_date'];

        foreach ($validateDateFields as $field) {
            if ($this->{$field} && !Util::validateDate($this->{$field})) {
                return Error::NewAppError("model.order.is_valid.{$field}.app_error", 'Order.isValid', null, "", StatusBadRequest);
            }
        }

        return true;
    }
}
