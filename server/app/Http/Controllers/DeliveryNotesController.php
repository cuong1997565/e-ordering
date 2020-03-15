<?php

namespace App\Http\Controllers;

use App\App\DeliveryNoteRepository;
use App\App\SaleOrderRepository;
use App\App\TransactionRepository;
use App\Events\DeliverNoteDomain\Context\DnConfirmedContext;
use App\Events\DeliverNoteDomain\DnConfirmed;
use App\Http\Context\Context;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteItem;
use App\Models\DiscountItem;
use App\Models\DiscountType;
use App\Models\Error;
use App\Models\SaleOrder;
use Illuminate\Support\Facades\DB;

class DeliveryNotesController extends Controller
{

    public function index()
    {
        $dn = DeliveryNote::with('distributor', 'factory', 'user')->getDynamic();

        $this->output_json(['data' => $dn], 200);
    }

    public function getDNById($id)
    {
        $info = DeliveryNote::with('distributor', 'factory', 'user', 'items','discountItems')->where('id', $id)->getDynamic();

        $dn = SaleOrder::with('sale_order_items.product.productstore', 'sale_order_items.grade', 'sale_order_items.uom')
            ->join('delivery_note_items', 'sale_orders.id', '=', 'delivery_note_items.so_id')
            ->where('delivery_note_items.dn_id', $id)
            ->leftJoin('sale_order_items', 'sale_order_items.id','=', 'delivery_note_items.so_item_id')
            ->select('sale_orders.*')
            ->groupBy('sale_orders.id')
            ->get()->all();

        $this->output_json(['data' => $dn, 'info' => $info], 200);
    }

    public function createDeliveryNote() {
        if (isset($this->data['items']) && is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items'], true);
            $this->request->replace($this->data);
        }
        $this->validate($this->request, [
            'items' => 'required',
            'items.*.so_item_id' => 'required|integer',
            'items.*.so_id' => 'required|integer',
            'items.*.deliver_quantity' => 'required',
            'items.*.store_id' => 'required|integer',
            'items.*.discount_type' => 'required_with:items.*.discount|integer|in:'.DeliveryNoteItem::$discountCustomType.','.DeliveryNoteItem::$discountPercentType,
            'items.*.discount' => 'required_with:items.*.discount_type',
        ], [
            'items.*.deliver_quantity.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver_quantity']),
            'items.*.so_item_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'so_item_id']),
            'items.*.so_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'so_id']),
            'items.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'items']),
            'items.*.so_item_id.integer' => trans('messages.api.integer_param.app_error', ['Name' => 'so_item_id']),
            'items.*.required' => trans('messages.api.integer_param.app_error', ['Name' => 'items']),
            'items.*.store_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'store_id']),
            'items.*.store_id.integer' => trans('messages.api.integer_param.app_error', ['Name' => 'store_id']),
            'items.*.discount_type.in' => trans('messages.api.in_value_param.app_error', ['Name' => 'discount_type']),
            'items.*.discount_type.required_with' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'discount_type']),
            'items.*.discount.required_with' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'discount']),
        ]);
        $data = $this->validateAndMapDn(DeliveryNote::$deliveryDraftStatus);

        $dn = $data['dn'];
        $items = $data['items'];
        $discountItems = $data['discount_item'];
        $result = (new DeliveryNoteRepository())->createDeliveryNote($dn, $items, $discountItems);

        $this->output_json_client($result, 200);
    }

    public function confirmDeliveryDirect() {
        if (isset($this->data['items']) && is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items'], true);
            $this->request->replace($this->data);
        }

        $this->validate($this->request, [
            'items' => 'required',
            'items.*.so_item_id' => 'required|integer',
            'items.*.so_id' => 'required|integer',
            'items.*.deliver_quantity' => 'required',
            'items.*.store_id' => 'required|integer',
            'items.*.discount_type' => 'required_with:items.*.discount|integer|in:'.DeliveryNoteItem::$discountCustomType.','.DeliveryNoteItem::$discountPercentType,
            'items.*.discount' => 'required_with:items.*.discount_type',
        ], [
            'items.*.deliver_quantity.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver_quantity']),
            'items.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'items']),
            'items.*.so_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'so_id']),
            'items.*.so_item_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'so_item_id']),
            'items.*.so_item_id.integer' => trans('messages.api.integer_param.app_error', ['Name' => 'so_item_id']),
            'items.*.store_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'store_id']),
            'items.*.store_id.integer' => trans('messages.api.integer_param.app_error', ['Name' => 'store_id']),
            'items.*.discount_type.in' => trans('messages.api.in_value_param.app_error', ['Name' => 'discount_type']),
            'items.*.discount_type.required_with' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'discount_type']),
            'items.*.discount.required_with' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'discount']),
        ]);

        $data = $this->validateAndMapDn(DeliveryNote::$deliveryDraftStatus);

        $dn = $data['dn'];
        $items = $data['items'];
        $discountItems = $data['discount_item'];

        $result = (new DeliveryNoteRepository())->createDeliveryNote($dn, $items, $discountItems);

        if (is_object($result) && get_class($result) == Error::class) {
            $this->output_json_client($result, 200);
        }

        $result = (new DeliveryNoteRepository())->confirmDeliveryNote($result);

        $this->output_json_client($result, 200);
    }

    protected function validateAndMapDn($type, $deliveryNoteId = null, $isUpdate = false, $originStatus = null) {
        $saleOrderItemsInRequest = collect($this->data['items'])->groupBy('so_id');

        $saleOrders = (new SaleOrderRepository())->getSaleOrdersByIds($saleOrderItemsInRequest->keys()->toArray());

        if (is_object($saleOrders) && get_class($saleOrders) == Error::class) {
            $this->output_json_client($saleOrders, 200);
        }

        $itemsAfterFill = [];
        $totalAmountDn = 0;
        foreach ($saleOrders as $key => $saleOrder) {
            if (!(new DeliveryNoteRepository())->allowCreateDeliveryNoteFromSo($saleOrder)) {
                $error = Error::NewAppError('model.dn.from.so.not_allow', 'DeliveryNotesController.createDeliveryNoteFromSo', null, '', StatusBadRequest);
                $this->output_json_client($error, 200);
            }
            $soItems = (new SaleOrderRepository())->getSaleOrderItemsByIds(array_map(function ($item) {
                return $item['so_item_id'];
            }, $saleOrderItemsInRequest[$saleOrder->id]->toArray()));

            if ((is_object($soItems) && get_class($soItems) == Error::class)) {
                $soItems->Id = 'controller.dn.sale_order_items.get_by_ids.not_found';
                $this->output_json_client($soItems, 200);
            }

            // Check items request from multiple distributors
            $checkDistributorId = null;
            foreach ($soItems as $soItem) {
                if (!$checkDistributorId) {
                    $checkDistributorId = $soItem->sale_order->distributor_id;
                }
                if ($checkDistributorId != $soItem->sale_order->distributor_id) {
                    $error = Error::NewAppError('model.dn.from.so.not_allow_from_multiple_distributor', 'DeliveryNotesController.createDeliveryNoteFromSo', null, '', StatusBadRequest);
                    $this->output_json_client($error, 200);
                }
            }

            // check and map
            // Check item ids in request that allow to deliver or not
            $items = $saleOrder->sale_order_items;
            $itemOpennings = $items->filter(function ($item, $key) {
                return $item->status == SO_ITEM_OPEN_STATUS;
            });

            $itemOpenningKeyByIds = $itemOpennings->keyBy('id');
            foreach ($saleOrderItemsInRequest[$saleOrder->id] as $key => $item) {
                if (!isset($itemOpenningKeyByIds[$item['so_item_id']])) {
                    $error = Error::NewAppError('model.dn_item.from.so.not_allow', 'DeliveryNotesController.createDeliveryNoteFromSo', null, '', StatusBadRequest);
                    $this->output_json_client($error, 200);
                }
                if (($quantity = $itemOpenningKeyByIds[$item['so_item_id']]->remaining_quantity) < $item['deliver_quantity']) {
                    $error = Error::NewAppError('model.dn_item.from.so.exceed_remaining', 'DeliveryNotesController.createDeliveryNoteFromSo', ['Quantity' => $quantity], '', 422);
                    $this->output_json_client($error, 200);
                }
                $itemInSaleOrder = $itemOpenningKeyByIds[$item['so_item_id']];
                $itemOriginAmount = DeliveryNoteItem::getAmount($item['deliver_quantity'], $itemInSaleOrder->unit_price);
                $itemAfterDiscount = DeliveryNoteItem::getAmountAfterDiscount($itemOriginAmount, $item['discount'], $item['discount_type']);
                $totalAmountDn += $itemAfterDiscount;
                $itemWillFill = array_merge($item, $itemInSaleOrder->replicate()->toArray(),
                    [
                        'distributor_id' => $saleOrder->distributor_id,
                        'factory_id' => $saleOrder->factory_id,
                        'amount' => $itemOriginAmount,
                        'amount_after_discount' => $itemAfterDiscount,
                        'so_id' => $item['so_id']
                    ]
                );
                $itemsAfterFill[] = (new DeliveryNoteItem())->fill($itemWillFill);
            }
        }
        $discount_fill = [];

        $totalAmountDnAfterDiscount = $totalAmountDn;

        if (isset($this->data['discount_for_dn'])) {
            $discount_for_dn = json_decode($this->data['discount_for_dn'], true);
            if (is_array($discount_for_dn) || is_object($discount_for_dn))
            {
                foreach ($discount_for_dn as $discount) {
                   if( $discount['discount_type_id'] != '' ) {
                       $discount_type = DiscountType::find($discount['discount_type_id']);

                       if ($discount_type->is_percentage == null) {
                           $discount_fill[] = (new DiscountItem())->fill(
                               [
                                   'discount_type_id' => $discount['discount_type_id'],
                                   'discount_amount' => $discount['discount_value']
                               ]
                           );
                           $totalAmountDnAfterDiscount = $totalAmountDnAfterDiscount - $discount['discount_value'];
                       } else {
                           if ($discount_type->is_custom_rate != null) {
                               $discount_fill[] = (new DiscountItem())->fill(
                                   [
                                       'discount_type_id' => $discount['discount_type_id'],
                                       'discount_rate' => $discount['discount_value']
                                   ]
                               );
                           }
                           $rate = $discount_type->is_custom_rate != null ? $discount['discount_value'] : $discount_type->discount_rate;
                           if ($discount_type->is_stack_discount != null) {
                               $totalAmountDnAfterDiscount = $totalAmountDnAfterDiscount - $totalAmountDnAfterDiscount * $rate;
                           } else {
                               $totalAmountDnAfterDiscount = $totalAmountDnAfterDiscount - $totalAmountDn * $rate;
                           }

                       }
                   }
                }

            }
        }

        if (!$isUpdate) {
            $status = null;
            if ($type == DeliveryNote::$deliveryConfirmStatus) {
                $isOverLimit = (new TransactionRepository())->checkIsLimitOverForDistributorId($checkDistributorId, $totalAmountDnAfterDiscount);

                if ($isOverLimit) {
                    $status = DeliveryNote::$deliveryWaitingApproveWhenOver;
                } else {
                    $status = DeliveryNote::$deliveryConfirmStatus;
                }

            }
            if ($type == DeliveryNote::$deliveryDraftStatus) {
                $status = DeliveryNote::$deliveryDraftStatus;
            }
        }

        $dn = (new DeliveryNote())->fill(array_merge($saleOrder->replicate()->toArray(), [
            'id' => $deliveryNoteId,
            'amount' => $totalAmountDn,
            'status' => is_null($originStatus) ? $status : $originStatus,
            'amount_after_discount' => $totalAmountDnAfterDiscount,
            'notes' => isset($this->data['notes']) ? $this->data['notes'] : ''
            ]));

        return ['dn' => $dn, 'items' => $itemsAfterFill, 'discount_item' => $discount_fill];
    }

    public function confirmDeliveryNote($deliveryNoteId) {
        $deliveryNote = (new DeliveryNoteRepository())->getDeliveryNote($deliveryNoteId);
        if (is_object($deliveryNote) && get_class($deliveryNote) == Error::class) {
            return $deliveryNote;
        }

        $result = (new DeliveryNoteRepository())->confirmDeliveryNote($deliveryNote);

        $this->output_json_client($result, 200);
    }


    public function updateDeliveryNote($delivery_note_id) {
        if (isset($this->data['items']) && is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items'], true);
            $this->request->replace($this->data);
        }
        $this->validate($this->request, [
            'items' => 'required',
            'items.*.so_item_id' => 'required|integer',
            'items.*.so_id' => 'required|integer',
            'items.*.deliver_quantity' => 'required',
            'items.*.store_id' => 'required|integer',
            'items.*.discount_type' => 'required_with:items.*.discount|integer|in:'.DeliveryNoteItem::$discountCustomType.','.DeliveryNoteItem::$discountPercentType,
            'items.*.discount' => 'required_with:items.*.discount_type',
        ], [
            'items.*.deliver_quantity.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver_quantity']),
            'items.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'items']),
            'items.*.so_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'so_id']),
            'items.*.so_item_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'so_item_id']),
            'items.*.so_item_id.integer' => trans('messages.api.integer_param.app_error', ['Name' => 'so_item_id']),
            'items.*.store_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'store_id']),
            'items.*.store_id.integer' => trans('messages.api.integer_param.app_error', ['Name' => 'store_id']),
            'items.*.discount_type.in' => trans('messages.api.in_value_param.app_error', ['Name' => 'discount_type']),
            'items.*.discount_type.required_with' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'discount_type']),
            'items.*.discount.required_with' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'discount']),
        ]);

        $deliveryNote = (new DeliveryNoteRepository())->getDeliveryNote($delivery_note_id);

        if (is_object($deliveryNote) && get_class($deliveryNote) == Error::class) {
            $this->output_json_client($deliveryNote, 200);
        }

        if ($deliveryNote->status != DeliveryNote::$deliveryDraftStatus) {
            $error = Error::NewAppError('model.update_dn.from.not_draft.not_allow', 'DeliveryNotesController.updateDeliveryNote', [], '', StatusBadRequest);
            $this->output_json_client($error, 200);
        }
        $data = $this->validateAndMapDn(DeliveryNote::$deliveryDraftStatus, $delivery_note_id, true, $deliveryNote->status);

        $dn = $data['dn'];
        $items = $data['items'];
        $discountItems = $data['discount_item'];

        $result = (new DeliveryNoteRepository())->updateDeliveryNote1($dn, $items, $discountItems);

        $this->output_json_client($result, 200);
    }

    public function revertDeliveyNote($delivery_note_id, DeliveryNoteRepository $dnr) {
        if (isset($this->data['from_sale_order_items']) && is_string($this->data['from_sale_order_items'])) {
            $this->data['from_sale_order_items'] = json_decode($this->data['from_sale_order_items'], true);
            $this->request->replace($this->data);
        }


        $this->validate($this->request, [
            'from_sale_order_items' => 'required',
            'from_sale_order_items.*.so_id' => 'required|integer',
            'from_sale_order_items.*.so_item_id' => 'required|integer',
            'from_sale_order_items.*.dn_item_id' => 'required|integer',
            'from_sale_order_items.*.deliver_quantity' => 'required|check_can_reverse:'.$delivery_note_id,
            'from_sale_order_items.*.store_id' => 'required|integer',
        ],[
            'from_sale_order_items.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'from_sale_order_items']),
            'from_sale_order_items.*.so_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'so_id']),
            'from_sale_order_items.*.so_item_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'so_item_id']),
            'from_sale_order_items.*.dn_item_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'dn_item_id']),
            'from_sale_order_items.*.deliver_quantity.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver_quantity']),
            'from_sale_order_items.*.deliver_quantity.check_can_reverse' => trans('messages.api.quantity.sale_order_item.reverse.app_error'),
            'from_sale_order_items.*.store_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'store_id']),
            'from_sale_order_items.*.so_id' => trans('messages.api.integer_param.app_error', ['Name' => 'so_id']),
            'from_sale_order_items.*.store_id' => trans('messages.api.integer_param.app_error', ['Name' => 'store_id']),
            'from_sale_order_items.*.so_item_id' => trans('messages.api.integer_param.app_error', ['Name' => 'so_item_id']),
        ]);

        $dataDeliveryNote = $dnr->getDeliveryNote($delivery_note_id);
        if (is_object($dataDeliveryNote) && get_class($dataDeliveryNote) == Error::class) {
            $this->output_json_client($dataDeliveryNote, 200);
        }
        $this->data['from_sale_order_items'] = collect($this->data['from_sale_order_items'])->keyBy('so_item_id');
        $deliveryNote = (new DeliveryNoteRepository())->createDeliveryNoteFromSaleOrder($this->data['from_sale_order_items'], $this->data['notes'], $dataDeliveryNote->dn_number);

        if (is_object($deliveryNote) && get_class($deliveryNote) == Error::class) {
            $this->output_json_client($deliveryNote, 200);
        }
        $deliveryNote = $dnr->reverseDeliveryNote($deliveryNote);
        $this->output_json_client($deliveryNote, 200);
    }

    public function rejectDnByAdmin($delivery_note_id, DeliveryNoteRepository $deliveryNoteRepository) {
        $deliveryNote = $deliveryNoteRepository->getDeliveryNote($delivery_note_id);

        if ((is_object($deliveryNote)) && get_class($deliveryNote) == Error::class) {
            $this->output_json_client($deliveryNote, 200);
        }

        $newDelivery = $deliveryNoteRepository->changeStatusDeliveryNoteToRejected($deliveryNote);

        if (is_object($newDelivery) && get_class($newDelivery) == Error::class) {
            $this->output_status_fail($newDelivery);
        } else {
            $this->output_json_client('OK', 200);
        }
    }

    public function approveDnByAdmin($delivery_note_id, DeliveryNoteRepository $deliveryNoteRepository) {
        $deliveryNote = $deliveryNoteRepository->getDeliveryNote($delivery_note_id);

        if ((is_object($deliveryNote)) && get_class($deliveryNote) == Error::class) {
            $this->output_json_client($deliveryNote, 200);
        }

        $newDelivery = $deliveryNoteRepository->changeStatusDeliveryNoteToApprove($deliveryNote);

        if (is_object($newDelivery) && get_class($newDelivery) == Error::class) {
            $this->output_status_fail($newDelivery);
        } else {
            $this->output_json_client('OK', 200);
        }
    }

    public function getClientDeliveryNote($id) {
        $dn = SaleOrder::with('sale_order_items.product.productstore', 'sale_order_items.grade', 'sale_order_items.uom',
            'sale_order_items:id,sale_order_id,product_id,grade_id,uom_id,sale_quantity,delivered_quantity,remaining_quantity,status,user_note,sale_note,product_attributes,customer_quantity')
            ->join('delivery_note_items', 'sale_orders.id', '=', 'delivery_note_items.so_id')
            ->where('delivery_note_items.so_id', $id)
            ->leftJoin('sale_order_items', 'sale_order_items.id','=', 'delivery_note_items.so_item_id')
            ->select('sale_orders.so_number','sale_orders.order_id','sale_orders.distributor_id','sale_orders.factory_id',
                'sale_orders.sale_person_id','sale_orders.status','sale_orders.note','sale_orders.id')
            ->groupBy('id')
            ->get()->all();
        $this->output_json(['data' => $dn], 200);
    }

    public function approveAndConfirmDnByAdmin($delivery_note_id, DeliveryNoteRepository $deliveryNoteRepository) {

        $deliveryNote = $deliveryNoteRepository->getDnAndDnItems($delivery_note_id);

        if ((is_object($deliveryNote)) && get_class($deliveryNote) == Error::class) {
            $this->output_json_client($deliveryNote, 200);
        }

        $newDelivery = $deliveryNoteRepository->changeStatusDeliveryNoteApproveToConfirm($deliveryNote);

        if (is_object($newDelivery) && get_class($newDelivery) == Error::class) {
            $this->output_status_fail($newDelivery);
        } else {
            $this->output_json_client('OK', 200);
        }
    }


}
