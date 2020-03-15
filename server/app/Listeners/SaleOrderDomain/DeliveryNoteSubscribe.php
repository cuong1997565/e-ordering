<?php
namespace App\Listeners\SaleOrderDomain;

use App\App\SaleOrderRepository;
use App\App\OrderItemRepository;
use App\Events\DeliverNoteDomain\DnApprovedConfirmed;
use App\Events\DeliverNoteDomain\DnConfirmed;
use App\Events\DeliverNoteDomain\DnReversed;
use App\Models\Error;

class DeliveryNoteSubscribe {
    public function onDeliverNoteConfirm(DnConfirmed $event) {
        $payload = $event->dnConfirmed->payload;
        $type = $event->dnConfirmed->type;
        $result = (new SaleOrderRepository())->updateMultipleSaleOrderItemsWithQuantity($payload, $type);
        if (is_object($result) && get_class($result) == Error::class) {
            throw new \Error($result->Id);
        }

        $resultOrder = (new OrderItemRepository())->DeliverNoteConfirmUpdateMultipleOrderItemsWithQuantity($payload, $type);
        if (is_object($resultOrder) && get_class($resultOrder) == Error::class) {
            throw new \Error($resultOrder->Id);
        }
    }

    public function DnApprovedConfirmedCanNotCheckCreditLimit(DnApprovedConfirmed $event) {
        $payload = $event->dnApprovedConfirmed->payload;
        $type = $event->dnApprovedConfirmed->type;
        $result = (new SaleOrderRepository())->updateMultipleSaleOrderItemsWithQuantity($payload, $type);
        if (is_object($result) && get_class($result) == Error::class) {
            throw new \Error($result->Id);
        }

        $resultOrder = (new OrderItemRepository())->DeliverNoteConfirmUpdateMultipleOrderItemsWithQuantity($payload, $type);
        if (is_object($resultOrder) && get_class($resultOrder) == Error::class) {
            throw new \Error($resultOrder->Id);
        }
    }

    public function onDeliverNoteReverse(DnReversed $event) {
        $payload = $event->dnReversed->payload;
        $type = $event->dnReversed->type;
        $result = (new SaleOrderRepository())->updateMultipleSaleOrderItemsQuantityWithReverse($payload, $type);


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
            'App\Events\DeliverNoteDomain\DnConfirmed',
            'App\Listeners\SaleOrderDomain\DeliveryNoteSubscribe@onDeliverNoteConfirm'
        );
        $events->listen(
            'App\Events\DeliverNoteDomain\DnApprovedConfirmed',
            'App\Listeners\SaleOrderDomain\DeliveryNoteSubscribe@DnApprovedConfirmedCanNotCheckCreditLimit'
        );
        $events->listen(
            'App\Events\DeliverNoteDomain\DnReversed',
            'App\Listeners\SaleOrderDomain\DeliveryNoteSubscribe@onDeliverNoteReverse'
        );
    }
}
