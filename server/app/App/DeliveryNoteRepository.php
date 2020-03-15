<?php
namespace App\App;

use App\Events\DeliverNoteDomain\Context\DnReversedContext;
use App\Events\DeliverNoteDomain\Context\DnWaitingForConfirmedContext;
use App\Events\DeliverNoteDomain\DnReversed;
use App\Events\DeliverNoteDomain\DnWaitingForConfirmed;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteItem;
use App\Models\Error;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use App\Store\DeliveryNoteStore;
use App\Store\OrderStore;
use App\Store\SaleOrderItemStore;
use App\Store\SaleOrderStore;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DeliveryNoteRepository {
    public function allowCreateDeliveryNoteFromSo(SaleOrder $saleOrder) {
        return $saleOrder->status == SO_OPEN_STATUS;
    }

    public function createDeliveryNote(DeliveryNote $dn, $dnItems, $discountItems = null) {
        return (new DeliveryNoteStore())->saveDeliveryNote($dn, $dnItems, $discountItems);
    }

    public function updateDeliveryNote1(DeliveryNote $dn, $dnItems, $discountItems = null) {
        return (new DeliveryNoteStore())->updateDeliveryNote($dn, $dnItems, $discountItems);
    }

    public function confirmDeliveryNote(DeliveryNote $deliveryNote) {
        if ($deliveryNote->status != DeliveryNote::$deliveryDraftStatus && $deliveryNote->status != DeliveryNote::$deliveryApproved) {
            return Error::NewAppError('delivery_note.model.confirm.invalid.status', 'DeliveryNoteRepository.confirmDeliveryNote', ['Status' => $deliveryNote->status], null, StatusBadRequest);
        }
        if ($deliveryNote->status == DeliveryNote::$deliveryApproved) {
            $deliveryNote->status = DeliveryNote::$deliveryConfirmStatus;
        } else {
            $isOverLimit = (new TransactionRepository())->checkIsLimitOverForDistributorId($deliveryNote->distributor_id, $deliveryNote->amount_after_discount);

            if (is_object($isOverLimit) && get_class($isOverLimit) == Error::class) {
                return $isOverLimit;
            }


            if ($isOverLimit) {
                $deliveryNote->status = DeliveryNote::$deliveryWaitingApproveWhenOver;
                event(new DnWaitingForConfirmed(new DnWaitingForConfirmedContext($deliveryNote)));
            } else {
                $deliveryNote->status = DeliveryNote::$deliveryConfirmStatus;

            }
        }

        return (new DeliveryNoteStore())->save($deliveryNote);
    }
//    public function confirmDeliveryNote($deliveryNote) {
//        if ($deliveryNote->status == DeliveryNote::$deliveryConfirmStatus) {
//            $error = Error::NewAppError('delivery_note_repository.confirm.in_valid', 'DeliveryNoteRepository.confirmDeliveryNote', null, '', StatusBadRequest);
//            return $error;
//        }
//        $deliveryNote->status = DeliveryNote::$deliveryConfirmStatus;
//        DB::beginTransaction();
//        $deliveryNoteUpdated = (new DeliveryNoteStore())->save($deliveryNote);
//
//        if (is_object($deliveryNoteUpdated) && get_class($deliveryNoteUpdated) == Error::class) {
//            DB::rollBack();
//            return $deliveryNoteUpdated;
//        }
//
//        $items = $deliveryNoteUpdated->items;
//        $payload = [];
//        foreach ($items as $item) {
//            $payload[$item->so_item_id] = $item->deliver_quantity;
//        }
//        $transaction['amount'] = $deliveryNoteUpdated->amount_after_discount;
//        $transaction['reference'] = $deliveryNoteUpdated->id;
//        $transaction['distributor_id'] = $deliveryNoteUpdated->distributor_id;
//        $dnConfirmedPayload = new DnConfirmedContext($payload, DeliveryNote::$deliveryConfirmStatus, $transaction);
//
//        $results = event(new DnConfirmed($dnConfirmedPayload));
//
//        foreach ($results as $result) {
//            if (is_object($result) && get_class($result) == Error::class) {
//                DB::rollBack();
//                return $result;
//            }
//        }
//        DB::commit();
//        return $deliveryNoteUpdated;
//    }

    public function reverseDeliveryNote($deliveryNote) {
        if ($deliveryNote->status != DeliveryNote::$deliveryConfirmStatus && $deliveryNote->status != DeliveryNote::$deliveryDraftStatus) {
            $error = Error::NewAppError('delivery_note_repository.reverse.in_valid', 'DeliveryNoteRepository.reverseDeliveryNote', null, '', StatusBadRequest);
            return $error;
        }
        $deliveryNote->status = DeliveryNote::$deliveryReverseStatus;
        DB::beginTransaction();
        $deliveryNoteUpdated = (new DeliveryNoteStore())->save($deliveryNote);

        if (is_object($deliveryNoteUpdated) && get_class($deliveryNoteUpdated) == Error::class) {
            DB::rollBack();
            return $deliveryNoteUpdated;
        }

        $items = $deliveryNoteUpdated->items;

        $payload = [];
        foreach ($items as $item) {
            $payload[$item->so_item_id] = $item->deliver_quantity;
        }

        $creditAccountRepository = new CreditAccountRepository();

        $dataAccount = $creditAccountRepository->findCreditAccount($deliveryNoteUpdated->distributor_id);

        $transaction['amount'] = $deliveryNoteUpdated->amount_after_discount;
        $transaction['type'] = TRANSACTION_TYPE_CR;
        $transaction['credit_id'] = $dataAccount['id'];
        $transaction['distributor_id'] = $deliveryNoteUpdated->distributor_id;
        $transaction['reference'] = $deliveryNoteUpdated->dn_number;
        $transaction['description'] = 'Reversed DN';
        $transaction['is_manual'] = 0;
        $transaction['is_hold'] = 0;
        $dnReversedPayload = new DnReversedContext($payload, DeliveryNote::$deliveryReverseStatus, $transaction);

        $results = event(new DnReversed($dnReversedPayload));

        foreach ($results as $result) {
            if (is_object($result) && get_class($result) == Error::class) {
                DB::rollBack();
                return $result;
            }
        }

        DB::commit();
        return $deliveryNoteUpdated;
    }

    public function factoryDeliveryNoteItems($soItems, $data) {
        $amountTotal = 0;
        $delivery_note_items = $soItems->map(function ($item, $key) use ($data, &$amountTotal) {
            $deliveryNoteItem = new DeliveryNoteItem();
            foreach (DeliveryNoteItem::$mapFieldsFromSaleOrderItem as $saleItems => $deliveryItem) {
                $deliveryNoteItem->$deliveryItem = $item->$saleItems;
            }
            $dataFromRequest = $data[$item->id];
            $deliveryNoteItem->store_id = $dataFromRequest['store_id'];
            $deliveryNoteItem->deliver_quantity = $dataFromRequest['deliver_quantity'];
            $deliveryNoteItem->notes = $dataFromRequest['notes'];
            $deliveryNoteItem->so_item_id = $item->id;
            $deliveryNoteItem->amount = $amount = DeliveryNoteItem::getAmount($dataFromRequest['deliver_quantity'], $item->unit_price);
            if (isset($dataFromRequest['discount']) && isset($dataFromRequest['discount_type']) && $dataFromRequest['discount'] && is_numeric($dataFromRequest['discount_type'])) {
                $deliveryNoteItem->discount_type = $dataFromRequest['discount_type'];
                $deliveryNoteItem->discount = $dataFromRequest['discount'];
                $deliveryNoteItem->amount_after_discount = DeliveryNoteItem::getAmountAfterDiscount($amount, $dataFromRequest['discount'], $dataFromRequest['discount_type']);
                if ($deliveryNoteItem->amount_after_discount < 0) {
                    $deliveryNoteItem->amount_after_discount = 0;
                }
            } else {
                $deliveryNoteItem->amount_after_discount = $deliveryNoteItem->amount;
            }
            $amountTotal += $deliveryNoteItem->amount_after_discount;
            return $deliveryNoteItem;
        });

        return [$delivery_note_items, $amountTotal];
    }

    public function createDeliveryNoteFromSaleOrder(Collection $data, $note, $dn_number) {
        $saleOrderRepository = new SaleOrderRepository();

        $saleOrderIds = $data->map(function ($item, $key) {
            return $item['so_id'];
        })->unique();
        $constraint = $this->checkConstraintSameDistributorAndFactory($saleOrderIds);
        if (is_object($constraint) && get_class($constraint) == Error::class) {
            return $constraint;
        }

        $saleOrderItemIds = $data->map(function ($item, $key) {
            return $item['so_item_id'];
        })->unique();

        $soItems = $saleOrderRepository->getSaleOrderItemsByIds($saleOrderItemIds->toArray());

        if (!$soItems) {
            $error = Error::NewAppError('model.count_so_items_by_so_ids.not_count', 'DeliveryNoteRepository.createDeliveryNoteFromSaleOrder', null, '', StatusBadRequest);
            return $error;
        }

        if (is_object($soItems) && get_class($soItems) == Error::class) {
            $error = Error::NewAppError('delivery_note_repository.count_so_items_by_so_ids.app_error', 'DeliveryNoteRepository.createDeliveryNoteFromSaleOrder', null, 'Cannot create delivery note without items', StatusBadRequest);
            return $error;
        }
        $totalAmount = 0;
        $distributor_id = null;
        $factory_id = null;
        $delivery_note_items = $soItems->map(function ($item, $key) use ($data, &$totalAmount, &$distributor_id, &$factory_id) {
            $deliveryNoteItem = new DeliveryNoteItem();
            foreach (DeliveryNoteItem::$mapFieldsFromSaleOrderItem as $saleItems => $deliveryItem) {
                $deliveryNoteItem->$deliveryItem = $item->$saleItems;
            }

            $dataFromRequest = $data[$item->id];
            $deliveryNoteItem->store_id = $dataFromRequest['store_id'];
            $deliveryNoteItem->deliver_quantity = $dataFromRequest['deliver_quantity'];
            $deliveryNoteItem->notes = $dataFromRequest['notes'];
            $deliveryNoteItem->so_item_id = $item->id;
            $deliveryNoteItem->so_id = $data[$item->id]['so_id'];
            if (isset($data[$item->id]['dn_item_id']) && $data[$item->id]['dn_item_id']) {
                $deliveryNoteItem->revert_from_item_id = $data[$item->id]['dn_item_id'];
            }
            $distributor_id = $item->sale_order->distributor_id;
            $factory_id = $item->sale_order->factory_id;
            $deliveryNoteItem->amount = $amount = DeliveryNoteItem::getAmount($dataFromRequest['deliver_quantity'], $item->unit_price);
            if (isset($dataFromRequest['discount']) && isset($dataFromRequest['discount_type']) && $dataFromRequest['discount'] && is_numeric($dataFromRequest['discount_type'])) {
                $deliveryNoteItem->discount_type = $dataFromRequest['discount_type'];
                $deliveryNoteItem->discount = $dataFromRequest['discount'];
                $deliveryNoteItem->amount_after_discount = DeliveryNoteItem::getAmountAfterDiscount($amount, $dataFromRequest['discount'], $dataFromRequest['discount_type']);
                if ($deliveryNoteItem->amount_after_discount < 0) {
                    $deliveryNoteItem->amount_after_discount = 0;
                }
            } else {
                $deliveryNoteItem->amount_after_discount = $deliveryNoteItem->amount;
            }
            $totalAmount += $deliveryNoteItem->amount_after_discount;
            return $deliveryNoteItem;
        });

        $distributor = (new DistributorRepository())->getDistributor($distributor_id);

        if (is_object($distributor) && get_class($distributor) == Error::class) {
            return $distributor;
        }
        $deliveryNote = new DeliveryNote();
        $deliveryNote->amount = $totalAmount;
        $deliveryNote->distributor_id = $distributor_id;
        $deliveryNote->factory_id = $factory_id;
        $deliveryNote->amount_after_discount = $totalAmount;
        $deliveryNote->sale_person_id = app('request')->curUser->id;
        $deliveryNote->status = DeliveryNote::$deliveryDraftStatus;
        $deliveryNote->notes = $note;
        DB::beginTransaction();
        $deliveryNoteCreated = $this->createOrUpdateDeliveryNote($deliveryNote);
        if (is_object($deliveryNoteCreated) && get_class($deliveryNoteCreated) == Error::class) {
            DB::rollBack();
            return $deliveryNoteCreated;
        }
//        $remain = sprintf("%03d", $deliveryNoteCreated->id % 999);
//        $time = Carbon::now()->format('y') . Carbon::now()->format('m') .Carbon::now()->format('d');
        $deliveryNoteCreated->dn_number = $dn_number.'_Reverse';
        $deliveryNoteCreated = $this->createOrUpdateDeliveryNote($deliveryNoteCreated);

        if (is_object($deliveryNoteCreated) && get_class($deliveryNoteCreated) == Error::class) {
            DB::rollBack();
            return $deliveryNoteCreated;
        }
        $result = $this->createDeliveryNoteItems($deliveryNoteCreated, $delivery_note_items);
        if (is_object($result) && get_class($result) == Error::class) {
            DB::rollBack();
            return $result;
        }
        DB::commit();
        return $deliveryNoteCreated;
    }

    public function checkConstraintSameDistributorAndFactory($saleOrderIds) {
        $isSameDistributor = (new DistributorRepository())->countDistributorsBySaleOrderIds($saleOrderIds->values()->toArray());
        if (is_object($isSameDistributor) && get_class($isSameDistributor) == Error::class) {
            return $isSameDistributor;
        }
        if ($isSameDistributor > 1) {
            $error = Error::NewAppError('model.count_distributors_from_so_ids_for_dn.app_error', 'DeliveryNoteRepository.createDeliveryNoteFromSaleOrder', null, '', StatusBadRequest);
            return $error;
        }
    }

    public function deleteMultiItemsByDnId($deliveryNoteId) {
        return (new DeliveryNoteStore())->deleteDnItemsByDeliveryNoteId($deliveryNoteId);
    }
    public function getDeliveryNote($deliveryNoteId) {
        return (new DeliveryNoteStore())->get($deliveryNoteId);
    }

    public function getDeliveryNoteItemsByIds($deliveryNoteIds) {
        return (new DeliveryNoteStore())->getDnItemsByIds($deliveryNoteIds);
    }

    public function changeStatusDeliveryNoteApproveToConfirm($deliveryNote) {
        if(!($deliveryNote->status == DeliveryNote::$deliveryApproved)) {
            $error = Error::NewAppError('model.delivery_note.change_status.invalid_status',
                'DeliveryNoteRepository.changeStatusDeliveryNoteApproveToConfirm', [],
                'status: '.$deliveryNote->status, StatusBadRequest);
            return $error;
        }
        $deliveryNote->status = DeliveryNote::$deliveryConfirmStatus;
        return (new DeliveryNoteStore())->changeStatusDeliveryNoteApproveToConfirm($deliveryNote);

    }

    public function getDnAndDnItems($deliveryNoteIds) {
        return (new DeliveryNoteStore())->getDnAndDnItems($deliveryNoteIds);
    }

    public function createOrUpdateDeliveryNote(DeliveryNote $deliveryNote)
    {
        return (new DeliveryNoteStore())->save($deliveryNote);
    }

    public function createDeliveryNoteItems(DeliveryNote $deliveryNote, Collection $deliveryNoteItems) {
        $deliveryNoteItems = $deliveryNoteItems->map(function ($item) use ($deliveryNote) {
            $item->dn_id = $deliveryNote->id;
            return $item;
        });
        return (new DeliveryNoteStore())->saveMulti($deliveryNoteItems);
    }


    public function changeStatusDeliveryNoteToRejected($deliveryNote) {
        if (!($deliveryNote->status == DeliveryNote::$deliveryWaitingApproveWhenOver)) {
            $error = Error::NewAppError('model.delivery_note.change_status.invalid_status',
                'DeliveryNoteRepository.changeStatusDeliveryNoteToRejected', [],
                'status: '.$deliveryNote->status, StatusBadRequest);
            return $error;
        }

        $deliveryNote->status =  DeliveryNote::$deliveryReject;
        return (new DeliveryNoteStore())->changeStatusDeliveryNote($deliveryNote);
    }

    public function changeStatusDeliveryNoteToApprove($deliveryNote) {
        if (!($deliveryNote->status == DeliveryNote::$deliveryWaitingApproveWhenOver)) {
            $error = Error::NewAppError('model.delivery_note.change_status.invalid_status',
                'DeliveryNoteRepository.changeStatusDeliveryNoteToApprove', [],
                'status: '.$deliveryNote->status, StatusBadRequest);
            return $error;
        }

        $deliveryNote->status =  DeliveryNote::$deliveryApproved;
        return (new DeliveryNoteStore())->changeStatusDeliveryNote($deliveryNote);
    }
}
