<?php

namespace App\Models;
use App\Helpers\Util;
use App\Store\OrderStore;
use App\Store\SaleOrderStore;
use Carbon\Carbon;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class SaleOrder extends AppModel
{
    public $items;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'so_number',
        'order_id',
        'distributor_id',
        'factory_id',
        'price_list_id',
        'estimated_amount',
        'so_date',
        'sale_person_id', // Tham chieu toi bang user
        'status',
        'note'
    ];

    public $belongTo =
        [
            'factory'
        ];

    public function factory() {
        return $this->belongsTo(Factory::class,'factory_id');
    }

    public function distributor() {
        return $this->belongsTo(Distributor::class,'distributor_id');
    }

    public function order() {
        return $this->belongsTo(Distributor::class,'order_id');
    }

    public function sale() {
        return $this->belongsTo(User::class,'sale_person_id');
    }

    public function sale_order_items() {
        return $this->hasMany(SaleOrderItem::class, 'sale_order_id', 'id');
    }

    public function price_list(){
        return $this->belongsTo(PriceList::class);
    }

    public function user(){
        return $this->belongsTo(User::class,'sale_person_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'sale_order_items', 'sale_order_id', 'product_id')
            ->withPivot('sale_order_id','amount', 'sale_note', 'user_note','id','grade_id','uom_id',
                'sale_quantity','delivered_quantity','remaining_quantity','product_attributes','unit_price_id','unit_price','status');
    }

    public function saleOrderFromOrder(Order $order) {
        $error = $order->isValid();

        if (is_object($error) && get_class($error) == Error::class) {
            return $error;
        }
        $saleOrder = new SaleOrder();

        $saleOrder->so_number = 'SO-PRIME-'.$order->distributor->code.Carbon::now()->format('Ymd');
        $saleOrder->order_id = $order->id;
        $saleOrder->distributor_id = $order->distributor_id;
        $saleOrder->factory_id = $order->factory_id;
        $saleOrder->sale_person_id = app('request')->curUser->id;
        $saleOrder->status = SO_DRAFT_STATUS;
        $saleOrder->note = $order->note;
        $saleOrder->price_list_id = DEFAULT_PRICE_LIST;
        $saleOrder->so_date = null;
        $total = 0;
        foreach ($order->items as $key => $item) {
            if ($item->order_quantity && $item->order_quantity != 0) {
                $unit_price = $order->getDefaultPrice($item->product_id);
                foreach ($unit_price[0]->price_list_items as $u) {
                    $order->items[$key]->unit_price = $u->unit_price;
                    $order->items[$key]->unit_price_id = $u->price_list_id;
                }
                $total += $item->order_quantity * $item->unit_price;
            }
        }
        $saleOrder->estimated_amount = $total;
        return $saleOrder;
    }

    public function updateSaleOrderConfirmAdmin($item = null) {
        $result = (new SaleOrderStore())->updateSaleOrderConfirmAdmin($this, $item);
        return $result;
    }

    public function updateSaleOrderSubmitAdmin($item = null) {
        $result = (new SaleOrderStore())->updateSaleOrderSubmitAdmin($this, $item);
        return $result;
    }
    /*
     * $item_order : item order product
     * */
    public function createSaleOrderSubmitAdmin($item, $item_order) {
        $result = (new SaleOrderStore())->createSaleOrderSubmitAdmin($this, $item, $item_order);
        return $result;
    }

    public function isValid() {
        if (!($this->status == SO_ITEM_DRAFT_STATUS || $this->status == SO_ITEM_OPEN_STATUS || $this->status == SO_ITEM_CLOSE_STATUS)) {
            return Error::NewAppError('model.sale_order.is_valid.status.app_error', 'Order.isValid', null, "id=".$this->id, StatusBadRequest);
        }

        if (!Util::validateInteger($this->factory_id)) {
            return Error::NewAppError('model.sale_order.is_valid.factory_id.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }
        if (!Util::validateInteger($this->distributor_id)) {
            return Error::NewAppError('model.sale_order.is_valid.distributor_id.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }
        if ($this->sale_person_id && !Util::validateInteger($this->sale_person_id)) {
            return Error::NewAppError('model.sale_order.is_valid.sale_person_id.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }
        if ($this->canceled_date && !Util::validateDate($this->canceled_date)) {
            return Error::NewAppError('model.sale_order.is_valid.canceled_date.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }
        if ($this->approved_date && !Util::validateDate($this->approved_date)) {
            return Error::NewAppError('model.sale_order.is_valid.approved_date.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }
        if ($this->rejected_date && !Util::validateDate($this->rejected_date)) {
            return Error::NewAppError('model.sale_order.is_valid.rejected_date.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }
        if ($this->completed_date && !Util::validateDate($this->completed_date)) {
            return Error::NewAppError('model.sale_order.is_valid.completed_date.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }
        if ($this->processing_date && !Util::validateDate($this->processing_date)) {
            return Error::NewAppError('model.sale_order.is_valid.processing_date.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }
        if ($this->confirm_date && !Util::validateDate($this->confirm_date)) {
            return Error::NewAppError('model.sale_order.is_valid.confirm_date.app_error', 'Order.isValid', null, "", StatusBadRequest);
        }
        return true;
    }
}
