<?php
namespace App\Listeners\PoOrderDomain;

use App\App\OrderItemRepository;
use App\App\OrderRepository;
use App\Events\PoDomain\PoUpdateStatusClose;
use App\Events\PoDomain\PoCreateAuto;
use App\Models\Error;

class OrderSubscribe {

    public function orderUpdateStatusClose(PoUpdateStatusClose $event) {
        $payload = $event->poUpdateStatusCloseContext->payload;
        /*
         * update status develing to close
         * */
        $result = (new OrderRepository())->changeStatusOrderToClose($payload);
        if (is_object($result) && get_class($result) == Error::class) {
            throw new \Error($result->Id);
        }
    }

    public function orderCreateAuto(PoCreateAuto $event) {
        $newSale = $event->poCreateAuto->newsale;
        $order_item = $event->poCreateAuto->orderItem;
        /*
        * create order auto
        * */
        $result = (new OrderRepository())->saleAutoCreateOrder($newSale, $order_item);
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
            'App\Events\PoDomain\PoUpdateStatusClose',
            'App\Listeners\PoOrderDomain\OrderSubscribe@orderUpdateStatusClose'
        );

        $events->listen(
            'App\Events\PoDomain\PoCreateAuto',
            'App\Listeners\PoOrderDomain\OrderSubscribe@orderCreateAuto'
        );
    }
}
