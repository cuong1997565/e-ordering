<?php
namespace App\App;

use App\Models\Error;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use App\Store\OrderStore;
use App\Store\SaleOrderItemStore;
use App\Store\SaleOrderStore;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class SaleOrderItemRepository {
    public function createSaleOrderItem(SaleOrder $saleOrder, Collection $saleOrderItems)
    {
        $saleOrderItems = $saleOrderItems->map(function ($item) use ($saleOrder) {
            $item->sale_order_id = $saleOrder->id;
            return $item;
        });
        return (new SaleOrderItemStore())->save($saleOrderItems);
    }
}
