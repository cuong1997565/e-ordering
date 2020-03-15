<?php
namespace App\Listeners\SaleOrderDomain;

use App\App\SaleOrderRepository;
use App\App\OrderItemRepository;
use App\Events\SaleDomain\OrderUpdateAutoSale;
use App\Events\DeliverNoteDomain\DnReversed;
use App\Models\Error;

class SaleOrderSubscribe {
    public function updateOrderIdToSale(OrderUpdateAutoSale $event) {
        $payload = $event->orderUpdateAutoSale->payload;

        $result = (new SaleOrderRepository())->updateOrderIdToSale($payload);

        if (is_object($result) && get_class($result) == Error::class) {
            throw new \Error($result->Id);
        }

    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\SaleDomain\OrderUpdateAutoSale',
            'App\Listeners\SaleOrderDomain\SaleOrderSubscribe@updateOrderIdToSale'
        );

    }
}
