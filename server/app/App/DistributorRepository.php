<?php
namespace App\App;

use App\Models\DeliveryNote;
use App\Models\DeliveryNoteItem;
use App\Models\Error;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use App\Store\DistributorStore;
use App\Store\FactoryStore;
use App\Store\OrderStore;
use App\Store\SaleOrderStore;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DistributorRepository {
    public function getDistributor($distributor)
    {
        return (new DistributorStore())->get($distributor);
    }

    public function countDistributorsBySaleOrderIds($saleOrderIds) {
        return (new DistributorStore())->countDistributorBySaleOrderIds($saleOrderIds);
    }

    public function createDeliveryNoteFromSaleOrder(Collection $data) {
        $saleOrderRepository = new SaleOrderRepository();

        $saleOrderIds = $data->map(function ($item, $key) {
            return $item['so_id'];
        })->unique();

        $saleOrderItemIds = $data->map(function ($item, $key) {
            return $item['so_item_id'];
        })->unique();

        $soItems = $saleOrderRepository->getSaleOrderItemsByIds($saleOrderItemIds->toArray());

        if (is_object($soItems) && get_class($soItems) == Error::class) {
            return $soItems;
        }

        $amount = 0;
        $delivery_note_items = $soItems->map(function ($item, $key) use ($data, &$amount) {
            $deliveryNoteItem = new DeliveryNoteItem();
            foreach (DeliveryNoteItem::$mapFieldsFromSaleOrderItem as $saleItems => $deliveryItem) {
                $deliveryNoteItem->$deliveryItem = $item->$saleItems;
            }
            $dataFromRequest = $data[$item->id];
            $deliveryNoteItem->store_id = $dataFromRequest['store_id'];
            $deliveryNoteItem->deliver_quantity = $dataFromRequest['deliver_quantity'];
            $deliveryNoteItem->notes = $dataFromRequest['notes'];
            $deliveryNoteItem->amount = $amount = DeliveryNoteItem::getAmount($dataFromRequest['deliver_quantity'], $item->unit_price);
            if (isset($dataFromRequest['discount']) && isset($dataFromRequest['discount_type']) && $dataFromRequest['discount'] && is_numeric($dataFromRequest['discount_type'])) {
                $deliveryNoteItem->discount_type = $dataFromRequest['discount_type'];
                $deliveryNoteItem->amount_after_discount = DeliveryNoteItem::getAmountAfterDiscount($amount, $dataFromRequest['discount'], $dataFromRequest['discount_type']);
                if ($deliveryNoteItem->amount_after_discount < 0) {
                    $deliveryNoteItem->amount_after_discount = 0;
                }
            } else {
                $deliveryNoteItem->amount_after_discount = $deliveryNoteItem->amount;
            }
            $amount += $deliveryNoteItem->amount_after_discount;
            return $deliveryNoteItem;
        });

        $deliveryNote = new DeliveryNote();
        $deliveryNote->amount = $amount;
        response()->json($deliveryNote)->send(); die;
        $saleOrders = $saleOrderRepository->getSaleOrdersByIds($saleOrderItemIds);

        if (is_object($saleOrders) && get_class($saleOrders) == Error::class) {
            return $saleOrders;
        }

        $delivery_notes = $saleOrders->map(function ($item, $key) {

        });
        response()->json($saleOrders[0]->replicate()->toArray())->send(); die;
//        $saleOrders = $saleOrders->map(function ($item) use ($data, $saleOrderItemIds) {
//            $item->sale_order_items = $item->sale_order_items->filter(function ($value, $key) use ($data, $saleOrderItemIds) {
//                $saleOrderId = $value['sale_order_id'];
//                foreach ($saleOrderItemIds as $saleOrderItemId) {
//                    if (isset($saleOrderItemId[$saleOrderId])) {
//                        return in_array($value['id'], $saleOrderItemId[$saleOrderId]);
//                    }
//                }
//                return false;
//            });
//            return $item;
//        });


    }

    public function productGetDistributor($product_id, $distributor_id) {
        return (new DistributorStore())->productGetDistributor($product_id, $distributor_id);

    }
}
