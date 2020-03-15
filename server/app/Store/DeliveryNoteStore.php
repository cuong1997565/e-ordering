<?php

namespace App\Store;

use App\App\CreditAccountRepository;
use App\DeveliveryNote\Events\DeliveryNoteConfirmedFromApprovedForDashboard;
use App\Events\DeliverNoteDomain\Context\DnApprovedContext;
use App\Events\DeliverNoteDomain\Context\DnConfirmAfterApprovedContext;
use App\Events\DeliverNoteDomain\Context\DnConfirmedContext;
use App\Events\DeliverNoteDomain\Context\DnDraftedContext;
use App\Events\DeliverNoteDomain\DnApproved;
use App\Events\DeliverNoteDomain\DnConfirmAfterApproved;
use App\Events\DeliverNoteDomain\DnConfirmed;
use App\Events\DeliverNoteDomain\DnDrafted;
use App\Events\DeliverNoteDomain\Context\DnApprovedConfirmedContext;
use App\Events\DeliverNoteDomain\DnApprovedConfirmed;
use App\Models\AppModel;
use App\Models\Customer;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteItem;
use App\Models\Distributor;
use App\Models\Factory;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Error;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class DeliveryNoteStore
{
    public function deleteDnItemsByDeliveryNoteId($deliveryNoteId) {
        DB::beginTransaction();
        try {
            $deliveryNote = DeliveryNoteItem::where('dn_id', $deliveryNoteId)->delete();
        } catch (\Exception $e) {
            // Rollback Transaction
            DB::rollBack();
            return Error::NewAppError('store.delivery_note.deleting.app_error', 'DeliveryNoteStore.deleteDnItemsByDeliveryNoteId', null, "dn_id=" . $deliveryNoteId . ', ' . $e->getMessage(),StatusInternalServerError);
        }

        if($deliveryNote == null)
        {
            // Rollback Transaction
            DB::rollBack();
            return Error::NewAppError('store.delivery_note.deleted.app_error', 'DeliveryNoteStore.deleteDnItemsByDeliveryNoteId', null, "dn_id=" . $deliveryNoteId,StatusNotFound);
        }
        DB::commit();
    }
    // get by delivery id
    public function get($id) {
        try {
            $deliveryNote = DeliveryNote::find($id);
        } catch (\Exception $e) {
            return Error::NewAppError('store.delivery_note.finding.app_error', 'DeliveryNoteStore.get', null, "id=" . $id . ', ' . $e->getMessage(),StatusInternalServerError);
        }

        if($deliveryNote == null)
        {
            return Error::NewAppError('store.delivery_note.finded.app_error', 'DeliveryNoteStore.get', null, "id={$id}",StatusNotFound);

        }

        return $deliveryNote;
    }

    //get by delivery note and delivery note  item
    public function getDnAndDnItems($deliveryNoteIds) {
        try {
            $deliveryNoteItems = DeliveryNote::where('id', $deliveryNoteIds)->with('items')->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.delivery_note.finding.app_error', 'DeliveryNoteStore.getDnAndDnItems',
                null, $e->getMessage(),StatusInternalServerError);
        }

        return $deliveryNoteItems;
    }

    public function getDnItemsByIds($deliveryNoteIds) {
        try {
            $deliveryNoteItems = DeliveryNoteItem::whereIn('ids', $deliveryNoteIds)->with('delivery_note')->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.delivery_note_items.finding.app_error', 'DeliveryNoteStore.getDnItemsByIds', null, $e->getMessage(),StatusInternalServerError);
        }

        return $deliveryNoteItems;
    }

    public function saveDeliveryNote(DeliveryNote $deliveryNote, $dnItems, $discountItems = null) {
        return $this->upsertDeliveryNoteWithTransaction($deliveryNote, $dnItems, $discountItems);
    }
    public function updateDeliveryNote(DeliveryNote $deliveryNote, $dnItems, $discountItems = null) {
        return $this->upsertDeliveryNoteWithTransaction($deliveryNote, $dnItems, $discountItems);
    }

    public function save(DeliveryNote $deliveryNote) {
        return $this->upsertDeliveryNoteWithTransaction($deliveryNote);
    }

    public function saveMulti(Collection $deliveryNoteItems) {
        return $this->upsertDeliveryNoteWithTransaction($deliveryNoteItems);
    }
    public function upsertDeliveryNoteWithTransaction($deliveryNote, $dnItems = [], $discountItems = null) {
//        $error = $saleOrder->isValid();

//        if (is_object($error) && get_class($error) == Error::class) {
//            return $error;
//        }
        DB::beginTransaction();
        try {
            if (is_object($deliveryNote) && get_class($deliveryNote) == DeliveryNote::class) {
                $error = $deliveryNote->isValid();
                if (is_object($error) && get_class($error) == Error::class) {
                    return $error;
                }
                $deliveryNote = DeliveryNote::updateOrCreate(
                    ['id' => $deliveryNote->id],
                    $deliveryNote->toArray()
                );


                // Update dn_number
                if (!$deliveryNote->dn_number) {
                    $remain = sprintf("%03d", $deliveryNote->id % 999);
                    $time = Carbon::now()->format('y') . Carbon::now()->format('m') .Carbon::now()->format('d');
                    $deliveryNote->dn_number = 'DN-PRIME-'.$deliveryNote->distributor->code.'-'. $time .'-'.$remain;
                    $deliveryNote->save();
                }

                if (is_array($dnItems) && !empty($dnItems)) {
                    $deliveryNote->items()->delete();
                    $deliveryNote->items()->saveMany($dnItems);
                }

                if (is_array($discountItems) && !empty($discountItems)) {
                    $deliveryNote->discountItems()->delete();
                    $deliveryNote->discountItems()->saveMany($discountItems);
                }

                if($deliveryNote->status == DeliveryNote::$deliveryDraftStatus) {
                    event(new DnDrafted(new DnDraftedContext($deliveryNote)));
                }

                if($deliveryNote->status == DeliveryNote::$deliveryConfirmStatus) {
                    $items = $deliveryNote->items;

                    $payload = [];
                    foreach ($items as $item) {
                        $payload[$item->so_item_id] = $item->deliver_quantity;
                    }

                    $creditAccountRepository = new CreditAccountRepository();

                    $dataAccount = $creditAccountRepository->findCreditAccount($deliveryNote->distributor_id);
                    $transaction['amount'] = $deliveryNote->amount_after_discount;
                    $transaction['type'] = TRANSACTION_TYPE_DR;
                    $transaction['credit_id'] = $dataAccount['id'];
                    $transaction['distributor_id'] = $deliveryNote->distributor_id;
                    $transaction['reference'] = $deliveryNote->dn_number;
                    $transaction['description'] = 'Issued DN';
                    $transaction['is_manual'] = 0;
                    $transaction['is_hold'] = 0;
                    $dnConfirmPayload = new DnConfirmedContext($payload, DeliveryNote::$deliveryConfirmStatus, $transaction, $deliveryNote);
                    $results = event(new DnConfirmed($dnConfirmPayload));

                    foreach ($results as $result) {
                        if (is_object($result) && get_class($result) == Error::class) {
                            DB::rollBack();
                            return $result;
                        }
                    }
                }
            } elseif (is_object($deliveryNote) && get_class($deliveryNote) == \Illuminate\Database\Eloquent\Collection::class) {
                foreach ($deliveryNote as $item) {
                    $error = $item->isValid();
                    if (is_object($error) && get_class($error) == Error::class) {
                        return $error;
                    }
                    $item->save();
                }
            }

            DB::commit();
        } catch(\Exception $e) {
            // Rollback Transaction
            DB::rollBack();
            return Error::NewAppError('store.delivery_note_store.update_or_create.up_sert.app_error', 'DeliveryNoteStore.upsertDeliveryNoteWithTransaction', null, "id=" . $deliveryNote->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }
        return $deliveryNote;
    }

    public function UpdateupsertDeliveryNoteWithTransaction($deliveryNote, $dnItems = []) {

        DB::beginTransaction();
        try {
            if (is_object($deliveryNote) && get_class($deliveryNote) == DeliveryNote::class) {
                $error = $deliveryNote->isValid();
                if (is_object($error) && get_class($error) == Error::class) {
                    return $error;
                }
                $deliveryNote = DeliveryNote::updateOrCreate(
                    ['id' => $deliveryNote->id],
                    $deliveryNote->toArray()
                );


                // Update dn_number
                if (!$deliveryNote->dn_number) {
                    $remain = sprintf("%03d", $deliveryNote->id % 999);
                    $time = Carbon::now()->format('y') . Carbon::now()->format('m') .Carbon::now()->format('d');
                    $deliveryNote->dn_number = 'DN-PRIME-'.$deliveryNote->distributor->code.'-'. $time .'-'.$remain;
                    $deliveryNote->save();
                }

                if (is_array($dnItems) && !empty($dnItems)) {
                    $deliveryNote->items()->delete();
                    $deliveryNote->items()->saveMany($dnItems);
                }

                if($deliveryNote->status == DeliveryNote::$deliveryConfirmStatus) {
                    $items = $deliveryNote->items;

                    $payload = [];
                    foreach ($items as $item) {
                        $payload[$item->so_item_id] = $item->deliver_quantity;
                    }

                    $creditAccountRepository = new CreditAccountRepository();

                    $dataAccount = $creditAccountRepository->findCreditAccount($deliveryNote->distributor_id);
                    $transaction['amount'] = $deliveryNote->amount_after_discount;
                    $transaction['type'] = TRANSACTION_TYPE_DR;
                    $transaction['credit_id'] = $dataAccount['id'];
                    $transaction['distributor_id'] = $deliveryNote->distributor_id;
                    $transaction['reference'] = $deliveryNote->dn_number;
                    $transaction['description'] = 'Issued DN';
                    $transaction['is_manual'] = 0;
                    $transaction['is_hold'] = 0;
                    $dnConfirmPayload = new DnConfirmedContext($payload, DeliveryNote::$deliveryConfirmStatus, $transaction, $deliveryNote);
                    $results = event(new DnConfirmed($dnConfirmPayload));

                    foreach ($results as $result) {
                        if (is_object($result) && get_class($result) == Error::class) {
                            DB::rollBack();
                            return $result;
                        }
                    }
                }
            } elseif (is_object($deliveryNote) && get_class($deliveryNote) == \Illuminate\Database\Eloquent\Collection::class) {
                foreach ($deliveryNote as $item) {
                    $error = $item->isValid();
                    if (is_object($error) && get_class($error) == Error::class) {
                        return $error;
                    }
                    $item->save();
                }
            }

            DB::commit();
        } catch(\Exception $e) {
            // Rollback Transaction
            DB::rollBack();
            return Error::NewAppError('store.delivery_note_store.update_or_create.up_sert.app_error', 'DeliveryNoteStore.upsertDeliveryNoteWithTransaction', null, "id=" . $deliveryNote->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }
        return $deliveryNote;
    }

    public function getSaleOrdersForDistributor($distributorId) {
        try {
            $saleOrders = SaleOrder::where('distributor_id', $distributorId)->with('sale_order_items')->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.sale_order_store.get_saleorders_for_distributor.app_error', 'SaleOrderStore.getSaleOrdersForDistributor', null, "distributor_id=".$distributorId. ', '. $e->getMessage(), StatusInternalServerError);
        }

        return $saleOrders;
    }

    public function changeStatusDeliveryNote(DeliveryNote $deliveryNote) {
        DB::beginTransaction();
        try {
            $deliveryNote->save();
            event(new DnApproved(new DnApprovedContext($deliveryNote)));
            DB::commit();
        } catch (\Exception $e) {
            // Rollback Transaction
            DB::rollBack();
        }
        return $deliveryNote;
    }

    public function changeStatusDeliveryNoteApproveToConfirm ($deliveryNote) {
        DB::beginTransaction();
        try {
            $deliveryNote->save();
            if($deliveryNote->status == DeliveryNote::$deliveryConfirmStatus) {
                $items = $deliveryNote->items;

                $payload = [];
                foreach ($items as $item) {
                    $payload[$item->so_item_id] = $item->deliver_quantity;
                }


                $creditAccountRepository = new CreditAccountRepository();
                $dataAccount = $creditAccountRepository->findCreditAccount($deliveryNote->distributor_id);
                $transaction['amount'] = $deliveryNote->amount_after_discount;
                $transaction['type'] = TRANSACTION_TYPE_DR;
                $transaction['credit_id'] = $dataAccount['id'];
                $transaction['distributor_id'] = $deliveryNote->distributor_id;
                $transaction['reference'] = $deliveryNote->dn_number;
                $transaction['description'] = 'Issued DN';
                $transaction['is_manual'] = 0;
                $transaction['is_hold'] = 0;
                event(new DnConfirmAfterApproved(new DnConfirmAfterApprovedContext($deliveryNote)));

                $dnConfirmPayload = new DnApprovedConfirmedContext($payload, DeliveryNote::$deliveryConfirmStatus, $transaction, $deliveryNote);
                $results = event(new DnApprovedConfirmed($dnConfirmPayload));

                foreach ($results as $result) {
                    if (is_object($result) && get_class($result) == Error::class) {
                        DB::rollBack();
                        return $result;
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            // Rollback Transaction
            DB::rollBack();
            return Error::NewAppError('store.delivery_note_store.change_status_approve_to_confirm.up_sert.app_error',
                'DeliveryNoteStore.changeStatusDeliveryNoteApproveToConfirm', null, "id=" . $deliveryNote->id . ', '
                . $e->getMessage(), StatusInternalServerError);

        }
        return $deliveryNote;
    }
}
