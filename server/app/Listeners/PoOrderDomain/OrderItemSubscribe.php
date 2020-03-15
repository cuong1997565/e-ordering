<?php
namespace App\Listeners\PoOrderDomain;

use App\App\OrderItemRepository;
use App\App\OrderRepository;
use App\Events\PoDomain\PoUpdateQuantity;
use App\Events\PoDomain\PoUpdateStatus;
use App\Models\Error;

class OrderItemSubscribe {
    public function orderItemUpdateQuantity(PoUpdateQuantity $event) {
        $payload = $event->poUpdateContext->payload;

        $result = (new OrderItemRepository())->updateMultipleOrderItemsWithQuantity($payload);
        if (is_object($result) && get_class($result) == Error::class) {
            throw new \Error($result->Id);
        }
    }

    public function orderUpdateStatus(PoUpdateStatus $event) {
        $payload = $event->poUpdateStatusContext->payload;

        $result = (new OrderRepository())->changeStatusToOrderDelivering($payload);
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
            'App\Events\PoDomain\PoUpdateQuantity',
            'App\Listeners\PoOrderDomain\OrderItemSubscribe@orderItemUpdateQuantity'
        );

        $events->listen(
            'App\Events\PoDomain\PoUpdateStatus',
            'App\Listeners\PoOrderDomain\OrderItemSubscribe@orderUpdateStatus'
        );

    }
}
