<?php

namespace App\Store;

use App\Models\AppModel;
use App\Models\Customer;
use App\Models\Factory;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Error;
use App\Models\SaleOrder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class SaleOrderItemStore
{
    public function save(Collection $saleOrderItems) {
        return $this->upsertSaleItemOrderWithTransaction($saleOrderItems);
    }

    public function upsertSaleItemOrderWithTransaction(Collection $saleOrderItems) {
        foreach ($saleOrderItems as $saleOrderItem) {
            $error = $saleOrderItem->isValid();
            if (is_object($error) && get_class($error) == Error::class) {
                return $error;
            }
        }

        DB::beginTransaction();
        try {
            foreach ($saleOrderItems as $saleOrderItem) {
                $saleOrderItem->save();
            }

            DB::commit();
        } catch(\Exception $e) {
            // Rollback Transaction
            DB::rollBack();
            return Error::NewAppError('store.sale_order_item_store.update_or_create.up_sert.app_error', 'SaleOrderItemStore.upsertSaleItemOrderWithTransaction', null,  $e->getMessage(), StatusInternalServerError);
        }
        return $saleOrderItems;
    }

    /*
   * 0  => [
   *         'product_id' => 10,
   *         'sale_quantity' => 10,
   *         'order_id' => 12
   *    ]
  * */
    public function updateOrderItemWithQuantity($payload) {
        DB::beginTransaction();
        try {
            foreach ($payload as $itemId => $item) {
                 DB::table('order_products')
                    ->where('order_id' , $item['order_id'])
                    ->where('product_id', $item['product_id'])
                    ->update(array('sale_quantity' => $item['sale_quantity']));

                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.order_item.update_multiple_orderitems_with_quantity.app_error',
                'SaleOrderItemStore.updateOrderItemWithQuantity', null, $e->getMessage(), StatusInternalServerError);
        }
    }
}
