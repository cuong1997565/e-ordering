<?php
namespace App\App;

use App\Models\Error;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use App\Store\OrderStore;
use App\Store\SaleOrderStore;
use Carbon\Carbon;

class SaleOrderRepository {
    public function getOrders($limit ,$order_code, $factory_id, $status, $from_date1, $from_date2,  $to_date1, $to_date2)
    {
        $result = (new OrderStore())->getOrders($limit ,$order_code, $factory_id, $status, $from_date1, $from_date2,  $to_date1, $to_date2);
        return $result;
    }

    public function getOrder($orderId)
    {
        return (new OrderStore())->get($orderId);
    }

    public function changeStatusOrderToProcessing($order)
    {
        if ($order->status !== SUBMITED_ORDER) {
            $error = Error::NewAppError('model.order.change_status.invalid_status', 'OrderRepository.changeStatusOrderToReview', [], 'status: '.$order->status, StatusBadRequest);
            return $error;
        }

        $order->status = REVIEWING_ORDER;
        $order->processing_date = Carbon::now();
        return (new OrderStore())->adminUpdateOrder($order);
    }

    public function changeStatusOrderToApproved($order)
    {
        if (!($order->status == SUBMITED_ORDER || $order->status == REVIEWING_ORDER)) {
            $error = Error::NewAppError('model.order.change_status.invalid_status', 'OrderRepository.changeStatusOrderToApproved', [], 'status: '.$order->status, StatusBadRequest);
            return $error;
        }
        $order->status = SALES_APPROVED_ORDER;
        $order->approved_date = Carbon::now();
        $orderCreated =  (new OrderStore())->adminUpdateOrder($order);
        if (is_object($orderCreated) && get_class($orderCreated) == Error::class) {
            return $orderCreated;
        }

        $saleOrder = (new SaleOrder())->saleOrderFromOrder($orderCreated);
        if ($items = $orderCreated->items) {
            $saleOrderItems = (new SaleOrderItem())->saleOrderItemFromOrderProduct($items);
            return $saleOrderItems;
        }

    }

    public function changeStatusOrderToRejected($order)
    {
        if (!($order->status == SUBMITED_ORDER || $order->status == REVIEWING_ORDER || $order->status == WAITING_FOR_CONFIRM_ORDER)) {
            $error = Error::NewAppError('model.order.change_status.invalid_status', 'OrderRepository.changeStatusOrderToRejected', [], 'status: '.$order->status, StatusBadRequest);
            return $error;
        }

        $order->status = REJECT_BY_SALES_ORDER;
        $order->rejected_date = Carbon::now();
        return (new OrderStore())->adminUpdateOrder($order);
    }
    public function changeStatusOrderToCompleted($order)
    {
        if (!$order->status == SALES_APPROVED_ORDER) {
            $error = Error::NewAppError('model.order.change_status.invalid_status', 'OrderRepository.changeStatusOrderToCompleted', [], 'status: '.$order->status, StatusBadRequest);
            return $error;
        }

        $order->status = CLOSED_ORDER;
        $order->completed_date = Carbon::now();
        return (new OrderStore())->adminUpdateOrder($order);
    }

    /*
     * data note order
     * */
    public function changeStatusSaleToClose($sale) {
        if(!($sale->status = SO_OPEN_STATUS)) {
            $error = Error::NewAppError('model.order.change_status.invalid_status',
                'SaleOrderRepository.changeStatusSaleToClose', [], 'status: '.$sale->status, StatusBadRequest);
            return $error;
        }


        $sale->status = SO_CLOSE_STATUS;
        return (new SaleOrderStore())->changeStatusSaleToClose($sale);
    }


    public function getSaleOrderItemById($saleOrderItemId) {
        $result = (new SaleOrderStore())->getSaleOrderItemById($saleOrderItemId);
        return $result;
    }

    public function getSaleOrder($saleOrderId) {
        return (new SaleOrderStore())->get($saleOrderId);
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
        return (new SaleOrderStore())->updateMultipleSaleOrderItemsWithQuantity($payload, $type);
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
        return (new SaleOrderStore())->updateMultipleSaleOrderItemsQuantityWithReverse($payload, $type);
    }

    public function createOrUpdateSaleOrder(SaleOrder $saleOrder)
    {
        return (new SaleOrderStore())->save($saleOrder);
    }

    public function getSaleOrdersByIds($saleOrderIds) {
        return (new SaleOrderStore())->getSaleOrdersByIds($saleOrderIds);
    }

    public function getSaleOrderItemsByIds($saleOrderItemIds) {
        return (new SaleOrderStore())->getSaleOrderItemsByIds($saleOrderItemIds);
    }

    public function getSaleOrdersForDistributor($distributorId) {
        return (new SaleOrderStore())->getSaleOrdersForDistributor($distributorId);
    }

    public function getOrderAndSale($orderId) {
        return (new SaleOrderStore())->getOrderAndSale($orderId);
    }

    public function getSaleOrderFrontendByID($sale_id) {
        return (new SaleOrderStore())->getSaleOrderFrontendByID($sale_id);
    }

    public function checkRemainingQuantitySaleOrder($saleItem) {
         $totalRemainingQuantity = 0;
         foreach ($saleItem as $key => $value) {
            $totalRemainingQuantity += $value['remaining_quantity'];
         }
         return $totalRemainingQuantity == 0;
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
        return (new SaleOrderStore())->updateOrderIdToSale($payload);

    }
}
