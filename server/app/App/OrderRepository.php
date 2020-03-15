<?php
namespace App\App;

use App\Events\PoDomain\Context\PoAcceptedContext;
use App\Events\PoDomain\PoAccepted;
use App\Models\Error;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use App\Store\OrderStore;
use App\Store\SaleOrderItemStore;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderRepository{

    public function getOrders($limit ,$order_code, $factory_id, $status, $from_date1, $from_date2,  $to_date1, $to_date2, $distributor_id)
    {
        $result = (new OrderStore())->getOrders($limit ,$order_code, $factory_id, $status, $from_date1, $from_date2,  $to_date1, $to_date2, $distributor_id);
        return $result;
    }

    public function getOrder($orderId)
    {
        return (new OrderStore())->get($orderId);
    }

    public function changeStatusOrderToProcessing($order, $items = [])
    {
        if ($order->status !== SUBMITED_ORDER) {
            $error = Error::NewAppError('model.order.change_status.invalid_status', 'OrderRepository.changeStatusOrderToReview', [], 'status: '.$order->status, StatusBadRequest);
            return $error;
        }

        $order->status = REVIEWING_ORDER;
        $order->processing_date = Carbon::now();
        return (new OrderStore())->adminUpdateOrder($order, $items);
    }


    public function changeStatusOrderToApproved($order, $items)
    {

        if (!($order->status == SUBMITED_ORDER || $order->status == REVIEWING_ORDER)) {
            $error = Error::NewAppError('model.order.change_status.invalid_status', 'OrderRepository.changeStatusOrderToApproved', [], 'status: '.$order->status, StatusBadRequest);
            return $error;
        }

        $order->status = SALES_ACCEPTED_ORDER;
        $order->approved_date = Carbon::now();
        DB::beginTransaction();
        $orderUpdated =  (new OrderStore())->adminUpdateOrder($order, $items);

        if (is_object($orderUpdated) && get_class($orderUpdated) == Error::class) {
            DB::rollBack();
            return $orderUpdated;
        }
        /*
         * get items new update status order
         * */
        $orderUpdateId = $this->getOrder($orderUpdated->id);

        if (is_object($orderUpdateId) && get_class($orderUpdateId) == Error::class) {
            DB::rollBack();
            return $orderUpdateId;
        }

        $saleOrder = (new SaleOrder())->saleOrderFromOrder($orderUpdated);

        $saleOrderCreated = (new SaleOrderRepository())->createOrUpdateSaleOrder($saleOrder);

        if (is_object($saleOrderCreated) && get_class($saleOrderCreated) == Error::class) {
            DB::rollBack();
            return $saleOrderCreated;
        }

        $remain = sprintf("%03d", $saleOrderCreated->id % 999);
        $time = Carbon::now()->format('y') . Carbon::now()->format('m') .Carbon::now()->format('d');

        $saleOrderCreated->so_number = 'SO-PRIME-'.$saleOrderCreated->distributor->code.'-'. $time .'-'.$remain;

        $saleOrderCreated = (new SaleOrderRepository())->createOrUpdateSaleOrder($saleOrderCreated);

        if (is_object($saleOrderCreated) && get_class($saleOrderCreated) == Error::class) {
            DB::rollBack();
            return $saleOrderCreated;
        }

        //filter status accept
        $status_accept = $orderUpdateId->items->filter(function($item) {
            return $item->status == Accept_ITEM_ORDER;
        });


        
        if ($items = $status_accept) {
            $saleOrderItems = (new SaleOrderItem())->saleOrderItemFromOrderProduct($status_accept);
            $result = (new SaleOrderItemRepository())->createSaleOrderItem($saleOrderCreated, $saleOrderItems);

            if (is_object($result) && get_class($result) == Error::class) {
                DB::rollBack();
                return $result;
            }
        }
        event(new PoAccepted(new PoAcceptedContext($order, $saleOrder)));

        DB::commit();
        return $saleOrderCreated;
    }

    public function changeStatusOrderToRejected($order, $items = [])
    {

        if (!($order->status == SUBMITED_ORDER || $order->status == REVIEWING_ORDER
            || $order->status == WAITING_FOR_CONFIRM_ORDER)) {
            $error = Error::NewAppError('model.order.change_status.invalid_status', 'OrderRepository.changeStatusOrderToRejected', [], 'status: '.$order->status, StatusBadRequest);
            return $error;
        }
        $order->status =  REJECT_BY_SALES_ORDER;
        $order->rejected_date = Carbon::now();
        return (new OrderStore())->adminUpdateOrder($order, $items);
    }


    /*
     * $payload :
     * [0] => [
     *  'order_id' => 12,
     *  'note' => 'qeqwe qweqwe '
     * ]
     * */
    public function changeStatusOrderToClose($payload) {
         return (new OrderStore())->changeStatusOrderToClose($payload);
    }


    public function changeStatusOrderToCompleted($order, $items = [])
    {
        if (!$order->status == SALES_APPROVED_ORDER) {
            $error = Error::NewAppError('model.order.change_status.invalid_status', 'OrderRepository.changeStatusOrderToCompleted', [], 'status: '.$order->status, StatusBadRequest);
            return $error;
        }

        $order->status = CLOSED_ORDER;
        $order->completed_date = Carbon::now();
        return (new OrderStore())->adminUpdateOrder($order, $items);
    }



    public function changeStatusOrderItemReject($id) {
        return (new OrderStore())->changeStatusOrderItemReject($id);
    }

    public function changeStatusToOrderDelivering($payload) {
        return (new OrderStore())->changeStatusToOrderDelivering($payload);
    }

    public function saleAutoCreateOrder($dataSale , $dataOrderItem) {
        return (new  OrderStore())->saleAutoCreateOrder($dataSale, $dataOrderItem);
    }
}
