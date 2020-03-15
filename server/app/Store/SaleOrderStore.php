<?php

namespace App\Store;

use App\Events\PoDomain\Context\PoAcceptedContext;
use App\Events\PoDomain\PoAccepted;
use App\Events\SaleDomain\Context\SoClosedContext;
use App\Events\SaleDomain\Context\SoConfirmedContext;
use App\Events\SaleDomain\SoClosed;
use App\Events\SaleDomain\SoConfirmed;
use App\Models\DeliveryNote;
use App\Models\Error;
use App\Models\OrderProduct;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Events\PoDomain\Context\PoUpdateQuantityContext;
use App\Events\PoDomain\PoUpdateQuantity;
use App\Events\PoDomain\Context\PoUpdateStatusCloseContext;
use App\Events\PoDomain\PoUpdateStatusClose;
use App\Events\PoDomain\Context\PoCreateAutoContext;
use App\Events\PoDomain\PoCreateAuto;

class SaleOrderStore
{
    public function get($saleOrderId) {
        try {
            $saleOrder = SaleOrder::with('sale_order_items')->where('id', $saleOrderId)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.sale_order_store.get.finding.app_error', 'SaleOrderStore.get', null, "id=" . $saleOrderId . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        if (!$saleOrder) {
            return Error::NewAppError('store.sale_order_store.get.finded.app_error', 'SaleOrderStore.get', null, '', StatusInternalServerError);
        }

        return $saleOrder;
    }

    public function getSaleOrdersByIds($saleOrderIds) {
        if (!is_array($saleOrderIds)) {
            return Error::NewAppError('store.sale_order_store.get.get_by_multiple_ids.app_error', 'SaleOrderStore.getByMultipleIds', null, '', StatusBadRequest);
        }
        try {
            $saleOrders = SaleOrder::whereIn('id', $saleOrderIds)->with('sale_order_items')->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.sale_order_store.get.get_by_multiple_ids.app_error', 'SaleOrderStore.getByMultipleIds', null, $e->getMessage(), StatusInternalServerError);
        }
        if (!$saleOrders->count()) {
            return Error::NewAppError('store.sale_order_store.get.get_by_multiple_ids.app_error', 'SaleOrderStore.getByMultipleIds', null, '', StatusBadRequest);
        }
        return $saleOrders;
    }

    public function getSaleOrderItemsByIds($saleOrderItemIds) {
        if (!is_array($saleOrderItemIds)) {
            return Error::NewAppError('store.sale_order_store.get_sale_order_items_by_ids.invalid', 'SaleOrderStore.getSaleOrderItemsByIds', null, '', StatusBadRequest);
        }
        try {
            $saleOrderItems = SaleOrderItem::whereIn('id', $saleOrderItemIds)->with('sale_order')->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.sale_order_store.get_sale_order_items_by_ids.app_error', 'SaleOrderStore.getSaleOrderItemsByIds', null, $e->getMessage(), StatusInternalServerError);
        }

        return $saleOrderItems;
    }

    public function getSaleOrderItemById($saleOrderItemId) {
        try {
            $saleOrderItems = SaleOrderItem::where('id', $saleOrderItemId)->with('sale_order')->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.sale_order_store.get_sale_order_item_by_id.app_error', 'SaleOrderStore.getSaleOrderItemById', null, $e->getMessage(), StatusInternalServerError);
        }


        if (!$saleOrderItems) {
            return Error::NewAppError('store.sale_order_store.get_sale_order_item_by_ids.not_found.app_error', 'SaleOrderStore.getSaleOrderItemById', null, '', StatusInternalServerError);
        }

        return $saleOrderItems;
    }

    /**
     * left so_item_id, right: delivered_quantity
     * Example payload:
     *    [
     *         1 => 2.00,   // left so_item_id, right: delivered_quantity
     *         2 => 3.00
     *    ]
     */
    public function updateMultipleSaleOrderItemsWithQuantity($payload, $type) {
        DB::beginTransaction();
        try {
            foreach ($payload as $itemId => $quantity) {
                // For isolata, we need get and set
                $item = $this->getSaleOrderItemById($itemId);

                if (is_object($item) && get_class($item) == Error::class) {
                    return Error::NewAppError('store.sale_order_store.get_sale_order_item_by_id.app_error', 'SaleOrderStore.updateMultipleSaleOrderItemsWithQuantity', null, '', StatusBadRequest);
                }

                if ($type == DeliveryNote::$deliveryConfirmStatus) {
                    $max = $item->sale_quantity;
                    $item->delivered_quantity = $item->delivered_quantity + $quantity;

                    if ($item->delivered_quantity > $max) {
                        $item->delivered_quantity = $max;
                    }
                    $item->remaining_quantity = $item->sale_quantity - $item->delivered_quantity;
                    if ($item->remaining_quantity < 0) {
                        $item->remaining_quantity = 0;
                    }
                }
                if ($type == DeliveryNote::$deliveryReverseStatus) {
                    $max = $item->sale_quantity;
                    $item->delivered_quantity = $item->delivered_quantity - $quantity;
                    if ($item->delivered_quantity > $max) {
                        $item->delivered_quantity = $max;
                    }
                    if ($item->delivered_quantity < 0) {
                        $item->delivered_quantity = 0;
                    }
                    $item->remaining_quantity = $item->sale_quantity + $item->delivered_quantity;
                    if ($item->remaining_quantity > $max) {
                        $item->remaining_quantity = $max;
                    }
                }
                $item->save();
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.sale_order_store.update_multiple_saleorderitems_with_quantity.app_error', 'SaleOrderStore.updateMultipleSaleOrderItemsWithQuantity', null, $e->getMessage(), StatusInternalServerError);
        }
    }
    /**
     * left so_item_id, right: delivered_quantity
     * Example payload:
     *    [
     *         1 => 2.00,   // left so_item_id, right: delivered_quantity
     *         2 => 3.00
     *    ]
     */
    public function updateMultipleSaleOrderItemsQuantityWithReverse($payload, $type) {
        DB::beginTransaction();
        try {
            foreach ($payload as $itemId => $quantity) {
                // For isolata, we need get and set
                $item = $this->getSaleOrderItemById($itemId);

                if (is_object($item) && get_class($item) == Error::class) {
                    return Error::NewAppError('store.sale_order_store.get_sale_order_item_by_id.app_error', 'SaleOrderStore.updateMultipleSaleOrderItemsWithQuantity', null, '', StatusBadRequest);
                }

                if($type == DeliveryNote::$deliveryReverseStatus) {
                     $max = $item->sale_quantity;
                     $item->delivered_quantity = $item->delivered_quantity - $quantity;

                     if ($item->delivered_quantity > $max) {
                        $item->delivered_quantity = $max;
                     }

                     if ($item->delivered_quantity < 0) {
                        $item->delivered_quantity = 0;
                     }

                     $item->remaining_quantity = $item->sale_quantity - $item->delivered_quantity;

                     if ($item->remaining_quantity > $max) {
                        $item->remaining_quantity = $max;
                     }
                }
                $item->save();

                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.sale_order_store.update_multiple_saleorderitems_with_quantity.app_error', 'SaleOrderStore.updateMultipleSaleOrderItemsWithQuantity', null, $e->getMessage(), StatusInternalServerError);
        }
    }


    public function save(SaleOrder $saleOrder) {
        return $this->upsertSaleOrderWithTransaction($saleOrder);
    }

    public function upsertSaleOrderWithTransaction(SaleOrder $saleOrder) {
        $error = $saleOrder->isValid();

        if (is_object($error) && get_class($error) == Error::class) {
            return $error;
        }
        DB::beginTransaction();
        try {
            $saleOrder = SaleOrder::updateOrCreate(
                ['id' => $saleOrder->id],
                $saleOrder->toArray()
            );
//            $saleOrder->save();
            DB::commit();
        } catch(\Exception $e) {
            // Rollback Transaction
            DB::rollBack();
            return Error::NewAppError('store.sale_order_store.update_or_create.up_sert.app_error', 'SaleOrderStore.upsertSaleOrderWithTransaction', null, "id=" . $saleOrder->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }
        return $saleOrder;
    }

    public function updateSaleOrderWithTransaction() {

    }

    public function updateSaleOrderConfirmAdmin(SaleOrder $saleOrder, $items) {
        if (!$saleOrder->id || !is_integer($saleOrder->id)) {
            return Error::NewAppError('model.order.is_valid.id.app_error', 'OrderStore.updateSaleOrderConfirmAdmin', null, "id={$saleOrder->id}", StatusBadRequest);
        }

        $sale = SaleOrder::find($saleOrder->id);

        try {
            if (!$sale) {
                return Error::NewAppError('store.stores.sale.order.find.app_error', 'OrderStore.updateSaleOrderConfirmAdmin', null, "id=" . $saleOrder->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.sale.order.find.app_error', 'OrderStore.updateSaleOrderConfirmAdmin', null, "id=" . $saleOrder->id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $saleOrder->toInstanceArray()) {
                return Error::NewAppError('store.sale.order.save.app_error', 'OrderStore.updateSaleOrderConfirmAdmin', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.sale.order.save.app_error', 'OrderStore.updateSaleOrderConfirmAdmin', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        DB::beginTransaction();
        try {
            $remain = sprintf("%03d", $saleOrder->id % 999);
            $time = Carbon::now()->format('y') . Carbon::now()->format('m') .Carbon::now()->format('d');

            $saleOrder->so_number = 'SO-PRIME-'.$saleOrder->distributor->code.'-'.$time.'-'.$remain;


            $saleOrder->estimated_amount = null;
            $total = 0;

            foreach ($items as $item) {
                if ($item->amount && $item->amount != 0) {
                    $total += $item->amount;
                }
            }

            $saleOrder->estimated_amount = $total;

            if (isset($data['id'])) {
                unset($data['id']);
            }


            $sale->update($data);

            SaleOrderItem::where('sale_order_id', $saleOrder->id)->delete();


            if (is_array($items) && count($items)) {
                $sale->sale_order_items()->saveMany($items);
            }

            foreach ($items as $item) {
                $payload[] = [
                    'order_id' => $saleOrder->order_id,
                    'product_id' => $item->product_id,
                    'sale_quantity' => $item->sale_quantity
                ];
            }
            $poPayload = new PoUpdateQuantityContext($payload);

            $results = event(new PoUpdateQuantity($poPayload));

            event(new SoConfirmed(new SoConfirmedContext($saleOrder)));

            foreach ($results as $result) {
                if (is_object($result) && get_class($result) == Error::class) {
                    DB::rollBack();
                    return $result;
                }
            }


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.sale.order.save.app_error', 'OrderStore.updateSaleOrderConfirmAdmin', null,
                "id=" . $saleOrder->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $saleOrder;
    }

    /*
     * $item_order : item order product
     * */
    public function createSaleOrderSubmitAdmin(SaleOrder $saleOrder, $items, $item_order) {
        if ($saleOrder->id !== null && $saleOrder->id !== 0) {
            return Error::NewAppError('store.order.save.existing.app_error', 'SaleOrderStore.createSaleOrderSubmitAdmin', null, "id={$saleOrder->id}", StatusBadRequest);
        }

        $saleOrder->id = null;

        try {
            if (!$data = $saleOrder->toInstanceArray()) {
                return Error::NewAppError('store.sale.order.save.app_error', 'SaleOrderStore.createSaleOrderSubmitAdmin', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.sale.order.save.app_error', 'SaleOrderStore.createSaleOrderSubmitAdmin', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        DB::beginTransaction();
        try {
            $saleOrder->estimated_amount = null;
            $total = 0;

            foreach ($items as $item) {
                if ($item->amount && $item->amount != 0) {
                    $total += $item->amount;
                }
            }

            $saleOrder->estimated_amount = $total;

            $newSaleOrder = SaleOrder::create($data);

            $remain = sprintf("%03d", $newSaleOrder->id % 999);

            $time = Carbon::now()->format('y') . Carbon::now()->format('m') .Carbon::now()->format('d');

            $newSaleOrder->so_number = 'SO-PRIME-'.$newSaleOrder->distributor->code.'-'. $time .'-'.$remain;

            SaleOrder::find($newSaleOrder->id)->update(['so_number' =>  $newSaleOrder->so_number]);

            if (is_array($items) && count($items) > 0) {
                $newSaleOrder->sale_order_items()->saveMany($items);
            }

            $poCreate = new PoCreateAutoContext($newSaleOrder, $item_order);

            $results = event(new PoCreateAuto($poCreate));

            foreach ($results as $result) {
                if (is_object($result) && get_class($result) == Error::class) {
                    DB::rollBack();
                    return $result;
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.sale.order.save.app_error', 'SaleOrderStore.createSaleOrderSubmitAdmin', null,
                ', ' . $e->getMessage(), StatusInternalServerError);
        }

        DB::commit();

        return $saleOrder;
    }

    public function updateSaleOrderSubmitAdmin(SaleOrder $saleOrder, $items) {
        if (!$saleOrder->id || !is_integer($saleOrder->id)) {
            return Error::NewAppError('model.order.is_valid.id.app_error', 'OrderStore.updateSaleOrderSubmitAdmin', null, "id={$saleOrder->id}", StatusBadRequest);
        }

        $sale = SaleOrder::find($saleOrder->id);


        try {
            if (!$sale) {
                return Error::NewAppError('store.stores.sale.order.find.app_error', 'OrderStore.updateSaleOrderSubmitAdmin', null, "id=" . $saleOrder->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.sale.order.find.app_error', 'OrderStore.updateSaleOrderSubmitAdmin', null, "id=" . $saleOrder->id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $saleOrder->toInstanceArray()) {
                return Error::NewAppError('store.sale.order.save.app_error', 'OrderStore.updateSaleOrderSubmitAdmin', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.sale.order.save.app_error', 'OrderStore.updateSaleOrderSubmitAdmin', null, 'cannot convert instance to array ', StatusInternalServerError);
        }


        DB::beginTransaction();
        try {
            $data['status'] = $sale['status'];
            $remain = sprintf("%03d", $saleOrder->id % 999);
            $time = Carbon::now()->format('y') . Carbon::now()->format('m') .Carbon::now()->format('d');

            $saleOrder->so_number = 'SO-PRIME-'.$saleOrder->distributor->code.'-'.$time.'-'.$remain;


            $saleOrder->estimated_amount = null;
            $total = 0;

            foreach ($items as $item) {
                if ($item->amount && $item->amount != 0) {
                    $total += $item->amount;
                }
            }

            $saleOrder->estimated_amount = $total;

            if (isset($data['id'])) {
                unset($data['id']);
            }


            $sale->update($data);

            SaleOrderItem::where('sale_order_id', $saleOrder->id)->delete();


            if (is_array($items) && count($items)) {
                $sale->sale_order_items()->saveMany($items);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.sale.order.save.app_error', 'OrderStore.updateSaleOrderSubmitAdmin', null,
                "id=" . $saleOrder->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $saleOrder;
    }

    public function getSaleOrdersForDistributor($distributorId) {
        try {
            $saleOrders = SaleOrder::where('distributor_id', $distributorId)->with('sale_order_items')->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.sale_order_store.get_saleorders_for_distributor.app_error', 'SaleOrderStore.getSaleOrdersForDistributor', null, "distributor_id=".$distributorId. ', '. $e->getMessage(), StatusInternalServerError);
        }

        return $saleOrders;
    }

    public function getOrderAndSale($orderId){
        try {
            $saleOrder = SaleOrder::with(['factory', 'distributor','sale_order_items'])->where('order_id', $orderId)
                ->select('id','so_number','order_id','distributor_id','factory_id','status')->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.sale_order_store.get.finding.app_error', 'SaleOrderStore.get', null, "id=" . $orderId . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        if (!$saleOrder) {
            return Error::NewAppError('store.sale_order_store.get.finded.app_error', 'SaleOrderStore.get', null, '', StatusInternalServerError);
        }

        return $saleOrder;
    }

    public function getSaleOrderFrontendByID($saleID) {
        try {
            $saleOrder = SaleOrder::with(['factory', 'distributor','sale_order_items'])->where('id', $saleID)
                ->select('id','so_number','order_id','distributor_id','factory_id','status')->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.sale_order_store.get.finding.app_error', 'SaleOrderStore.get', null, "id=" . $saleID . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        if (!$saleOrder) {
            return Error::NewAppError('store.sale_order_store.get.finded.app_error', 'SaleOrderStore.get', null, '', StatusInternalServerError);
        }

        return $saleOrder;
    }


    /*
    * status sale order open to close
    * $note : note status close order
    * */
    public function changeStatusSaleToClose($sale) {
        DB::beginTransaction();
        try {
            $sale->save();
            //update status item sale order to close
            foreach ($sale->sale_order_items as $key => $value) {
                 DB::table('sale_order_items')
                    ->where('sale_order_id', $value['sale_order_id'])
                    ->where('product_id', $value['product_id'])
                    ->update(['status' => SO_ITEM_CLOSE_STATUS]);
            }

            event(new SoClosed(new SoClosedContext($sale)));

//            $payloadStatus[] = [
//                'order_id' => $sale->order_id,
//                'note' => $note
//            ];
//
//            /*
//             * po update status close
//             *
//             * */
//            $poPayloadStatus = new PoUpdateStatusCloseContext($payloadStatus);
//            $resultStatus = event(new PoUpdateStatusClose($poPayloadStatus));
//            event(new SoClosed(new SoClosedContext($sale)));
//            foreach ($resultStatus as $result) {
//                if (is_object($result) && get_class($result) == Error::class) {
//                    DB::rollBack();
//                    return $result;
//                }
//            }


            DB::commit();
        } catch (\Exception $e) {
            // Rollback Transaction
            DB::rollBack();
        }
        return $sale;
    }

    /*
    * update order id to sale order
    *
    * $payload : 0 => [
    *      'sale_id' : 1,
    *      'order_id' : 1
    * ]
    * */
    public function updateOrderIdToSale($payload) {
        DB::beginTransaction();
        try {
          foreach ($payload  as $value) {
              SaleOrder::find($value['sale_id'])->update(['order_id' => $value['order_id']]);
          }
          DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.update.order.id.to.sale.app_error', 'SaleOrderStore.updateOrderIdToSale', null . ', ' . $e->getMessage(), StatusInternalServerError);
        }
    }
}
