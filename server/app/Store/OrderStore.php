<?php

namespace App\Store;
use App\App\SaleOrderRepository;
use App\Events\PoDomain\Context\PoSubmitedContext;
use App\Events\PoDomain\Context\PoUpdateStatusContext;
use App\Events\PoDomain\PoSubmited;
use App\Events\PoDomain\PoUpdateStatus;
use App\Events\SaleDomain\OrderUpdateAutoSale;
use App\Events\SaleDomain\Context\OrderUpdateAutoSaleContext;
use App\Models\AppModel;
use App\Models\Customer;
use App\Models\DeliveryNote;
use App\Models\Distributor;
use App\Models\Factory;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PriceList;
use App\Models\Product;
use App\Models\Error;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class OrderStore
{
    use ValidatesAttributes;

    public function get($id)
    {
        if (filter_var($id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.order.is_valid.id.app_error', 'OrderStore.get', null, "id={$id}", StatusBadRequest);
        }

        try {
            $customer = Order::with('products', 'items.uom_front_end', 'factory')->where('id', $id)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.order.get.finding.app_error', 'OrderStore.get', null, "id=" . $id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        if ($customer == null) {
            return Error::NewAppError('store.order.get.find.app_error', 'OrderStore.get', null, "id={$id}", StatusNotFound);
        }

        return $customer;
    }

    public function getDefaultPrice($product_id)
    {
        $unit_price = PriceList::with(['price_list_items' => function ($q) use ($product_id) {
            $q->where('product_id', $product_id);
        }])->where('id', DEFAULT_PRICE_LIST)->get();

        return $unit_price;
    }

    public function getOrders($limit, $order_code, $factory_id, $status, $from_date1, $from_date2, $to_date1, $to_date2, $distributor_id)
    {
        if (!is_null($order_code) && !is_string($order_code)) {
            return Error::NewAppError('model.order.is_valid.order_code.app_error', 'OrderStore.getOrders', null, "name={$order_code}", StatusBadRequest);
        }

        if (!is_null($factory_id) && filter_var($factory_id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.order.is_valid.factory_id.app_error', 'OrderStore.getOrders', null, "name={$factory_id}", StatusBadRequest);
        }

        if (!is_null($distributor_id) && filter_var($distributor_id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.order.is_valid.distributor_id.app_error', 'OrderStore.getOrders', null, "name={$factory_id}", StatusBadRequest);
        }

        if (!is_null($status) && !is_numeric($status)) {
            return Error::NewAppError('model.order.is_valid.status.app_error', 'OrderStore.getOrders', null, "name={$status}", StatusBadRequest);
        }

        try {
            $result = new Order();

            $result = $result->with(['factory', 'customer']);

            if ($factory_id) {
                $result = $result->where('factory_id', $factory_id);
            }

            if ($distributor_id) {
                $result = $result->where('distributor_id', $distributor_id);
            }

            if ($order_code) {
                $result = $result->where('code', 'LIKE', "%$order_code%");
            }

            if ($status === "0" || $status) {
                $result = $result->where('status', $status);
            }

            if ($from_date1 && $to_date1) {
                $result = $result->whereBetween('created_at', [$from_date1, $to_date1]);
            }

            if ($from_date2 && $to_date2) {
                $result = $result->whereBetween('updated_at', [$from_date2, $to_date2]);
            }

            $result = $result->orderBy('id', 'desc');

            $result = $result->paginate($limit);

            return $result;
        } catch (\Exception $e) {
            return Error::NewAppError('store.order.search_order.app_error', 'OrderStore.getOrders', null, $e->getMessage(), StatusInternalServerError);

        }
    }

    public function save(Order $order, $products)
    {
        if ($order->id !== null && $order->id !== 0) {
            return Error::NewAppError('store.order.save.existing.app_error', 'OrderStore.save', null, "id={$order->id}", StatusBadRequest);
        }

        $order->id = null;
        try {
            if (!Factory::find($order->factory_id)) {
                return Error::NewAppError('store.stores.factory.find.app_error', 'OrderStore.save', null, "id=" . $order->factory_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.factory.find.app_error', 'OrderStore.save', null, "id=" . $order->factory_id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!Customer::find($order->creator_id)) {
                return Error::NewAppError('store.stores.customer.find.app_error', 'OrderStore.save', null, "id=" . $order->creator_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.customer.find.app_error', 'OrderStore.save', null, "id=" . $order->creator_id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $order->toInstanceArray()) {
                return Error::NewAppError('store.order.save.app_error', 'OrderStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.order.save.app_error', 'OrderStore.save', null, 'cannot convert instance to array ', StatusInternalServerError);
        }
        DB::beginTransaction();
        try {
            $newOrder = Order::create($data);
            event(new PoSubmited(new PoSubmitedContext($data)));
            $remain = sprintf("%03d", $newOrder->id % 999);
            $time = Carbon::now()->format('y') . Carbon::now()->format('m') .Carbon::now()->format('d');
            $newOrder->po_number = 'PO-PRIME-'.$newOrder->distributor->code.'-'. $time .'-'.$remain;
            Order::find($newOrder->id)->update(['po_number' =>  $newOrder->po_number]);

            if (isset($products) && is_array($products)) {
                foreach ($products as $key => $value) {
                    try {
                        if (!Product::find($value)) {
                            DB::rollBack();
                            return Error::NewAppError('store.products.update.finding.app_error', 'OrderStore.save', null, "id=" . $value, StatusBadRequest);
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return Error::NewAppError('store.products.update.finding.app_error', 'OrderStore.save', null, "id=" . $value . $e->getMessage(), StatusInternalServerError);
                    }
                }
                if (is_array($order->items) && count($order->items)) {
                    $newOrder->items()->saveMany($order->items);
                }
//                $order_save = Order::findOrFail($order->id);
//                $order_save->products()->sync($products);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.order.save.app_error', 'OrderStore.save', null, $e->getMessage(), StatusInternalServerError);
        }
        DB::commit();
        return $order;

    }

    public function updateDrafOrder($order, $products)
    {
        if (!$order->id || !is_integer($order->id)) {
            return Error::NewAppError('model.order.is_valid.id.app_error', 'OrderStore.update', null, "id={$order->id}", StatusBadRequest);
        }

        try {
            if (!Order::find($order->id)) {
                return Error::NewAppError('store.stores.order.find.app_error', 'OrderStore.update', null, "id=" . $order->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.order.find.app_error', 'OrderStore.update', null, "id=" . $order->id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!Factory::find($order->factory_id)) {
                return Error::NewAppError('store.stores.factory.find.app_error', 'OrderStore.update', null, "id=" . $order->factory_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.factory.find.app_error', 'OrderStore.update', null, "id=" . $order->factory_id . $e->getMessage(), StatusInternalServerError);
        }


        try {
            if (!Customer::find($order->creator_id)) {
                return Error::NewAppError('store.stores.customer.find.app_error', 'OrderStore.update', null, "id=" . $order->creator_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.customer.find.app_error', 'OrderStore.update', null, "id=" . $order->creator_id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $order->toInstanceArray()) {
                return Error::NewAppError('store.order.save.app_error', 'OrderStore.update', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.order.save.app_error', 'OrderStore.update', null, 'cannot convert instance to array ', StatusInternalServerError);
        }
        DB::beginTransaction();
        try {
            $orderStatus = Order::find($order->id);
            if ($orderStatus) {
                $data['status'] = $orderStatus['status'];
                $data['deliver_date'] = $orderStatus['deliver_date'];
                $data['canceled_date'] = $orderStatus['canceled_date'];
                $data['approved_date'] = $orderStatus['approved_date'];
                $data['rejected_date'] = $orderStatus['rejected_date'];
                $data['completed_date'] = $orderStatus['completed_date'];
                $data['processing_date'] = $orderStatus['processing_date'];
                $data['confirm_date'] = $orderStatus['confirm_date'];
            }

            if (isset($data['id'])) {
                unset($data['id']);
            }
            Order::find($order->id)->update($data);
            OrderProduct::where('order_id', $order->id)->delete();
            if (is_array($order->items) && count($order->items)) {
                $order->items()->saveMany($order->items);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.order.save.app_error', 'OrderStore.update', null, "id=" . $order->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }
        DB::commit();

        return $order;
    }

    public function adminUpdateOrder($order, $items)
    {
        $updatedOrder = $this->updateOrderWithTransaction($order, $items);
        // Handle post-transaction
        return $updatedOrder;
    }

    public function updateOrderWithTransaction(Order $order, $items)
    {
        $error = $order->isValid();
        if (is_object($error) && get_class($error) == Error::class) {
            return $error;
        }

        DB::beginTransaction();
        try {
          $order->save();
          if(count($items) > 0) {
              OrderProduct::where('order_id', $order->id)->delete();
              $order->items()->saveMany($items);
          }
          DB::commit();
        } catch (\Exception $e) {
            // Rollback Transaction
            DB::rollBack();
        }
        return $order;
    }

    public function updateOrder(Order $order, $product)
    {
        if (!$order->id || !is_integer($order->id)) {
            return Error::NewAppError('model.order.is_valid.id.app_error', 'OrderStore.update', null, "id={$order->id}", StatusBadRequest);
        }

        try {
            if (!Order::find($order->id)) {
                return Error::NewAppError('store.stores.order.find.app_error', 'OrderStore.update', null, "id=" . $order->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.order.find.app_error', 'OrderStore.update', null, "id=" . $order->id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!Factory::find($order->factory_id)) {
                return Error::NewAppError('store.stores.factory.find.app_error', 'OrderStore.update', null, "id=" . $order->factory_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.factory.find.app_error', 'OrderStore.update', null, "id=" . $order->factory_id . $e->getMessage(), StatusInternalServerError);
        }


        try {
            if (!Customer::find($order->creator_id)) {
                return Error::NewAppError('store.stores.customer.find.app_error', 'OrderStore.update', null, "id=" . $order->creator_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.customer.find.app_error', 'OrderStore.update', null, "id=" . $order->creator_id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $order->toInstanceArray()) {
                return Error::NewAppError('store.order.save.app_error', 'OrderStore.update', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.order.save.app_error', 'OrderStore.update', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        DB::beginTransaction();
        try {
            $orderStatus = Order::find($order->id);
            if ($orderStatus) {
                $data['canceled_date'] = $orderStatus['canceled_date'];
                $data['approved_date'] = $orderStatus['approved_date'];
                $data['rejected_date'] = $orderStatus['rejected_date'];
                $data['completed_date'] = $orderStatus['completed_date'];
                $data['processing_date'] = $orderStatus['processing_date'];
                $data['confirm_date'] = $orderStatus['confirm_date'];
                $remain = sprintf("%03d", $data['id'] % 999);
                $time = Carbon::now()->format('y') . Carbon::now()->format('m') .Carbon::now()->format('d');
                $po_number =  'PO-PRIME-'.$order->distributor->code.'-'.$time.'-'.$remain;
                $data['po_number'] = $po_number;
                if ($orderStatus['status'] === WAITING_FOR_DRAF_ORDER || $orderStatus['status'] === WAITING_FOR_CONFIRM_ORDER) {
                    $data['status'] = SUBMITED_ORDER;
                } else {
                    $data['status'] = $orderStatus['status'];
                }
            }

            if (isset($data['id'])) {
                unset($data['id']);
            }
            $orderStatus->update($data);

            OrderProduct::where('order_id', $order->id)->delete();

            if (is_array($order->items) && count($order->items)) {
                $order->items()->saveMany($order->items);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.order.save.app_error', 'OrderStore.update', null, "id=" . $order->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $order;
    }


    public function updateOrderClient(Order $order, $product)
    {
        if (!$order->id || !is_integer($order->id)) {
            return Error::NewAppError('model.order.is_valid.id.app_error', 'OrderStore.updateOrderClient', null, "id={$order->id}", StatusBadRequest);
        }

        try {
            if (!Order::find($order->id)) {
                return Error::NewAppError('store.stores.order.find.app_error', 'OrderStore.updateOrderClient', null, "id=" . $order->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.order.find.app_error', 'OrderStore.updateOrderClient', null, "id=" . $order->id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!Factory::find($order->factory_id)) {
                return Error::NewAppError('store.stores.factory.find.app_error', 'OrderStore.updateOrderClient', null, "id=" . $order->factory_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.factory.find.app_error', 'OrderStore.updateOrderClient', null, "id=" . $order->factory_id . $e->getMessage(), StatusInternalServerError);
        }


        try {
            if (!Customer::find($order->creator_id)) {
                return Error::NewAppError('store.stores.customer.find.app_error', 'OrderStore.updateOrderClient', null, "id=" . $order->creator_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.customer.find.app_error', 'OrderStore.updateOrderClient', null, "id=" . $order->creator_id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $order->toInstanceArray()) {
                return Error::NewAppError('store.order.save.app_error', 'OrderStore.updateOrderClient', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.order.save.app_error', 'OrderStore.updateOrderClient', null, 'cannot convert instance to array ', StatusInternalServerError);
        }
        DB::beginTransaction();
        try {
            $orderStatus = Order::find($order->id);
            if ($orderStatus) {
                $data['canceled_date'] = $orderStatus['canceled_date'];
                $data['approved_date'] = $orderStatus['approved_date'];
                $data['rejected_date'] = $orderStatus['rejected_date'];
                $data['completed_date'] = $orderStatus['completed_date'];
                $data['processing_date'] = $orderStatus['processing_date'];
                $data['confirm_date'] = $orderStatus['confirm_date'];

                if ($orderStatus['status'] === WAITING_FOR_DRAF_ORDER || $orderStatus['status'] === WAITING_FOR_CONFIRM_ORDER) {
                    $data['status'] = SUBMITED_ORDER;
                } else {
                    $data['status'] = $orderStatus['status'];
                }
            }

            if (isset($data['id'])) {
                unset($data['id']);
            }


            $orderStatus->update($data);

            OrderProduct::where('order_id', $order->id)->delete();

            if (is_array($order->items) && count($order->items)) {
                $order->items()->saveMany($order->items);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.order.save.app_error', 'OrderStore.updateOrderClient', null, "id=" . $order->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }
        DB::commit();

        return $order;
    }

    public function updateStatus($order)
    {
        if (!$order->id || !is_integer($order->id)) {
            return Error::NewAppError('model.order.is_valid.id.app_error', 'OrderStore.updateStatus', null, "id={$order->id}", StatusBadRequest);
        }

        try {
            if (!Order::find($order->id)) {
                return Error::NewAppError('store.stores.order.find.app_error', 'OrderStore.updateStatus', null, "id=" . $order->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.order.find.app_error', 'OrderStore.updateStatus', null, "id=" . $order->id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $order->toInstanceArray()) {
                return Error::NewAppError('store.order.save.app_error', 'OrderStore.updateStatus', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.order.save.app_error', 'OrderStore.updateStatus', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            $order_update = Order::find($order->id);
            $order_update->status = $order->status;
            $order_update->canceled_date = $order->canceled_date;
            $order_update->save();
        } catch (\Exception $e) {
            return Error::NewAppError('store.order.save.app_error', 'OrderStore.updateStatus', null, "id=" . $order->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $order;
    }

    public function changeStatusOrderItemReject($id) {
        DB::beginTransaction();
        try {
            $order_item = OrderProduct::find($id);

            if($order_item) {
                $order = OrderProduct::where('order_id',$order_item['order_id'])->where('status', Accept_ITEM_ORDER)->count();
                if($order > 1) {
                    $order_item->update(['status' => REJECT_ITEM_ORDER]);
                } else {
                    $order_item->update(['status' => REJECT_ITEM_ORDER]);
                    Order::find($order_item['order_id'])->update(['status'  => REJECT_BY_SALES_ORDER ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.change.status.order.item.app_error', 'OrderStore.changeStatusOrderItemReject', null,
                'cannot convert instance to array ', StatusInternalServerError);
        }
        return $id;
    }

    public function findOrderItemProduct($id, $product) {
        try {
            $orderItems = OrderProduct::where('order_id', $id)->where('product_id', $product)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.order_store.get_order_item_by_id.app_error', 'OrderStore.findOrderItemProduct', null, $e->getMessage(), StatusInternalServerError);
        }


        if (!$orderItems) {
            return Error::NewAppError('store.order_store.get_order_item_by_ids.not_found.app_error', 'OrderStore.findOrderItemProduct', null, '', StatusInternalServerError);
        }

        return $orderItems;
    }


    public function DeliverNoteConfirmUpdateMultipleOrderItemsWithQuantity($payload, $type) {
        DB::beginTransaction();
        try {
            $saleOrderRepository = new SaleOrderRepository();
            foreach ($payload as $itemId => $quantity) {
                // For isolata, we need get and set
                $item = $saleOrderRepository->getSaleOrderItemById($itemId);
                if (is_object($item) && get_class($item) == Error::class) {
                    return Error::NewAppError('store.sale_order_store.get_sale_order_item_by_id.app_error', 'OrderStore.DeliverNoteConfirmUpdateMultipleOrderItemsWithQuantity', null, '', StatusBadRequest);
                }

                $orderItem = $this->findOrderItemProduct($item->sale_order->order_id, $item['product_id']);

                if(is_object($orderItem) && get_class($orderItem) == Error::class) {
                    return Error::NewAppError('store.order_store.get_order_item_by_id.app_error', 'OrderStore.DeliverNoteConfirmUpdateMultipleOrderItemsWithQuantity', null, '', StatusBadRequest);
                }


                if ($type == DeliveryNote::$deliveryConfirmStatus) {
                    $max = $orderItem->sale_quantity;
                    $orderItem->delivery_quantity = $orderItem->delivery_quantity + $quantity;
                    if ($orderItem->delivery_quantity > $max) {
                        $orderItem->delivery_quantity = $max;
                    }
                    $orderItem->remaining_quantity = $orderItem->sale_quantity - $orderItem->delivery_quantity;
                    if ($orderItem->remaining_quantity < 0) {
                        $orderItem->remaining_quantity = 0;
                    }
                }
                if ($type == DeliveryNote::$deliveryReverseStatus) {
                    $max = $orderItem->sale_quantity;
                    $orderItem->delivery_quantity = $orderItem->delivery_quantity - $quantity;
                    if ($orderItem->delivery_quantity > $max) {
                        $orderItem->delivery_quantity = $max;
                    }
                    if ($orderItem->delivery_quantity < 0) {
                        $orderItem->delivery_quantity = 0;
                    }
                    $orderItem->remaining_quantity = $orderItem->sale_quantity + $orderItem->delivery_quantity;
                    if ($orderItem->remaining_quantity > $max) {
                        $orderItem->remaining_quantity = $max;
                    }
                }
                $orderItem->save();
                /*
                 * Update status order
                 * */
                $payloadStatus[] = [
                    'order_id' => $item->sale_order->order_id
                ];

                $poPayloadStatus = new PoUpdateStatusContext($payloadStatus);

                $resultStatus = event(new PoUpdateStatus($poPayloadStatus));

                foreach ($resultStatus as $result) {
                    if (is_object($result) && get_class($result) == Error::class) {
                        DB::rollBack();
                        return $result;
                    }
                }

                DB::commit();
            }
        } catch (\Exception $e) {

            DB::rollBack();
            return Error::NewAppError('store.order_store.update_multiple_orderitems_with_quantity.app_error', 'OrderStore.DeliverNoteConfirmUpdateMultipleOrderItemsWithQuantity', null, $e->getMessage(), StatusInternalServerError);
        }
    }

    /*
     * $payload : array(1) { [0]=> array(1) { ["order_id"]=>  "5" } }
     * */
    public function changeStatusToOrderDelivering($payload) {
        DB::beginTransaction();
        try {
            foreach ($payload as $itemId => $item) {
                // find id order
                $order = $this->get($item['order_id']);
                if(is_object($order) && get_class($order) == Error::class) {
                    return Error::NewAppError('store.order_store.get_order_item_by_id.app_error', 'OrderStore.changeStatusToOrderDelivering', null, '', StatusBadRequest);
                }

                if ($order['status'] == SALES_ACCEPTED_ORDER) {
                    $order['status'] = DELIVERING_ORDER;
                    $order->save();
                }
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.order_store.update_status_order_delivering.app_error',
                'OrderStore.changeStatusToOrderDelivering', null, $e->getMessage(),
                StatusInternalServerError);
        }
    }
    /*
     * $payload : array(1) {   ["order_id"]=>  "5", ["note"] => "qweqwe'wqe'qw'eqe qwe"  }
     * */
    public function changeStatusOrderToClose($payload) {
        DB::beginTransaction();
        try {
                // find id order
                $order = $this->get($payload['order_id']);
                if(is_object($order) && get_class($order) == Error::class) {
                    return Error::NewAppError('store.order_store.get_order_item_by_id.app_error', 'OrderStore.changeStatusToOrderDelivering', null, '', StatusBadRequest);
                }

                if ($order['status'] == DELIVERING_ORDER) {
                    $order['status'] = CLOSED_ORDER;
                    $order['note_status_close'] = $payload['note'];
                    $order->save();
                }
                DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.order_store.update_status_order_delivering.app_error',
                'OrderStore.changeStatusToOrderDelivering', null, $e->getMessage(),
                StatusInternalServerError);
        }
    }
    /*
     * $newOrder : [],
     * $orderItem : []
     * */
    public function saleAutoCreateOrder($saleOrder , $orderItem) {
        DB::beginTransaction();
        try {
            $order = new Order();
            $order->code  = str_random(10);
            $order->factory_id = $saleOrder->factory_id;
            $order->creator_id	= $saleOrder->sale_person_id;
            $order->status = SALES_ACCEPTED_ORDER;
            $order->deliver_date = Carbon::now()->toDateTimeString();
            $order->approved_date = Carbon::now()->toDateString();
            $order->type = TYPE_AUTO_ORDER;
            $order->distributor_id = $saleOrder->distributor_id;
            $order->save();

            $remain = sprintf("%03d", $order->id % 999);

            $time = Carbon::now()->format('y') . Carbon::now()->format('m') .Carbon::now()->format('d');

            $order->po_number = 'PO-PRIME-'.$order->distributor->code.'-'. $time .'-'.$remain;

            Order::find($order->id)->update(['po_number' =>  $order->po_number]);

            if (is_array($orderItem) && count($orderItem)) {
                $order->items()->saveMany($orderItem);
            }

            $payload[] = [
                'order_id' => $order->id,
                'sale_id' => $saleOrder->id
            ];

            $poCreate = new OrderUpdateAutoSaleContext($payload);

            $results = event(new OrderUpdateAutoSale($poCreate));

            foreach ($results as $result) {
                if (is_object($result) && get_class($result) == Error::class) {
                    DB::rollBack();
                    return $result;
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.order_store.create_order_auto.app_error',
                'OrderStore.saleAutoCreateOrder', null, $e->getMessage(),
                StatusInternalServerError);
        }
    }
}
