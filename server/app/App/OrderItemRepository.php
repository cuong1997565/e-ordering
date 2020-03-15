<?php

namespace App\App;

use App\Store\SaleOrderItemStore;
use App\Store\OrderStore;

class OrderItemRepository
{
    /*
     * 0  => [
     *         'product_id' => 10,
     *         'sale_quantity' => 10,
     *         'order_id' => 12
     *    ]
    * */
    public function updateMultipleOrderItemsWithQuantity($payload)
    {
        return (new SaleOrderItemStore())->updateOrderItemWithQuantity($payload);
    }

    public function DeliverNoteConfirmUpdateMultipleOrderItemsWithQuantity($payload, $type) {
        return (new OrderStore())->DeliverNoteConfirmUpdateMultipleOrderItemsWithQuantity($payload, $type);
    }
}
