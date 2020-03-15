<?php

namespace App\Http\Controllers;

use App\App\FactoryRepository;
use App\Models\Error;
use App\Models\SaleOrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\SaleOrder;
use App\Models\OrderProduct;
use App\App\SaleOrderRepository;
use App\Models\DeliveryNoteItem;

class SaleOrderController extends Controller
{

    public function getSaleOrderAdminByID($sale_order_id)
    {
        $saleorder = SaleOrder::with('factory', 'distributor', 'user','price_list.price_list_items')->where('id', $sale_order_id)->first();

        $pivot_item = SaleOrderItem::with('grade', 'uom', 'product.productstore')->where('sale_order_id', $saleorder->id)->get();

        $this->output_json(['data' => $saleorder, 'pivot_item' => $pivot_item], 200);
    }

    public function getSaleOrderAdmin()
    {
        $saleorder = SaleOrder::with('factory', 'distributor', 'user','price_list')->getDynamic();

        $this->output_json(['data' => $saleorder], 200);
    }

    public function updateSaleOrderConfirmAdmin($sale_id, SaleOrder $saleOrder) {
        if (is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items']);
            $this->data['items'] = array_map(function ($item) {
                if (is_object($item) && get_class($item) === \stdClass::class) {
                    $item = json_decode(json_encode($item), true);
                }
                return $item;
            }, $this->data['items']);
            $this->request->replace($this->data);

        }

        $this->data['id'] = $sale_id;

        if (is_array($this->data['items']) && count($this->data['items']) > 0) {
            $this->data['items'] = array_map(function ($item) {
                if (!isset($item['user_note'])) {
                    $item['user_note'] = '';
                }
                $newSaleOrderItem = new SaleOrderItem([
                    'product_id' => $item['product_id'],
                    'grade_id' => $item['grade_id'],
                    'uom_id' => $item['uom_id'],
                    'sale_quantity' => $item['sale_quantity'],
                    'delivered_quantity' => $item['delivered_quantity'],
                    'remaining_quantity' => $item['sale_quantity'],
                    'unit_price_id' => $item['unit_price_id'],
                    'amount' => $item['amount'],
                    'status' => $item['status'],
                    'user_note' => $item['user_note'],
                    'sale_note' => $item['sale_note'],
                    'product_attributes' => json_encode($item['product_attributes']),
                    'unit_price' => $item['unit_price'],
                    'customer_quantity' => $item['customer_quantity'],
                    'code_stock_order_product' => json_encode($item['code_stock_order_product'])
                ]);
                return $newSaleOrderItem;
            }, $this->data['items']);
        }

        $this->validate($this->request, [
            'items' => 'required',
            'items.*.sale_quantity' => 'required|check_sale_quantity:'.$sale_id. '|check_sale_quantity_min:' . $sale_id,
        ],[
            'items.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'from_sale_order_items']),
            'items.*.sale_quantity.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'sale quantity']),
            'items.*.sale_quantity.check_sale_quantity' => trans('messages.api.quantity.sale_order_item.app_error'),
            'items.*.sale_quantity.check_sale_quantity_min' => trans('messages.api.integer_param.app_error',  ['Name' => 'sale quantity'])
        ]);

        $saleOrder = $saleOrder->toModel($this->data);

        if ((isset($this->data['items']) && is_array($this->data['items']))) {
            $items = array_get($this->data, 'items', []);
            $result = $saleOrder->updateSaleOrderConfirmAdmin($items);
        } else {
            $result = $saleOrder->updateSaleOrderConfirmAdmin();
        }
        $this->output_json($result, 200);
    }

    public function updateSaleOrderSubmitAdmin($sale_id, SaleOrder $saleOrder) {
        if (is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items']);
            $this->data['items'] = array_map(function ($item) {
                if (is_object($item) && get_class($item) === \stdClass::class) {
                    $item = json_decode(json_encode($item), true);
                }
                return $item;
            }, $this->data['items']);
            $this->request->replace($this->data);
        }

        $this->data['id'] = $sale_id;

        if (is_array($this->data['items']) && count($this->data['items']) > 0) {
            $this->data['items'] = array_map(function ($item) {
                if (!isset($item['user_note'])) {
                    $item['user_note'] = '';
                }
                $newSaleOrderItem = new SaleOrderItem([
                    'product_id' => $item['product_id'],
                    'grade_id' => $item['grade_id'],
                    'uom_id' => $item['uom_id'],
                    'sale_quantity' => $item['sale_quantity'],
                    'delivered_quantity' => $item['delivered_quantity'],
                    'remaining_quantity' => $item['sale_quantity'],
                    'unit_price_id' => $item['unit_price_id'],
                    'amount' => $item['amount'],
                    'status' => $item['status'],
                    'user_note' => $item['user_note'],
                    'sale_note' => $item['sale_note'],
                    'product_attributes' => json_encode($item['product_attributes']),
                    'unit_price' => $item['unit_price'],
                    'customer_quantity' => $item['customer_quantity'],
                    'code_stock_order_product' => json_encode($item['code_stock_order_product'])
                ]);
                return $newSaleOrderItem;
            }, $this->data['items']);
        }

        $this->validate($this->request, [
            'items' => 'required',
            'items.*.sale_quantity' => 'required|check_sale_quantity:'.$sale_id . '|check_sale_quantity_min:' . $sale_id,
        ],[
            'items.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'from_sale_order_items']),
            'items.*.sale_quantity.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'sale quantity']),
            'items.*.sale_quantity.check_sale_quantity' => trans('messages.api.quantity.sale_order_item.app_error'),
            'items.*.sale_quantity.check_sale_quantity_min' => trans('messages.api.integer_param.app_error',  ['Name' => 'sale quantity'])
        ]);

        $saleOrder = $saleOrder->toModel($this->data);

        if ((isset($this->data['items']) && is_array($this->data['items']))) {
            $items = array_get($this->data, 'items', []);
            $result = $saleOrder->updateSaleOrderSubmitAdmin($items);
        } else {
            $result = $saleOrder->updateSaleOrderSubmitAdmin();
        }
        $this->output_json($result, 200);
    }

    public function getOrderAboutSaleOrder($order_id) {
        $result = SaleOrder::where('order_id', $order_id)->first();
        $this->output_json(['data' => $result], 200);
    }

    public function getSaleOrderFrontendByID($sale_order_id, SaleOrderRepository $saleOrderRepository)
    {
        $saleorder = $saleOrderRepository->getSaleOrderFrontendByID($sale_order_id);

        if ((is_object($saleorder)) && get_class($saleorder) == Error::class) {
            $this->output_json_client($saleorder, 200);
        }

        $pivot_item = SaleOrderItem::with(['grade', 'uom', 'product.productstore'])
            ->where('sale_order_id', $saleorder->id)
            ->select('id','sale_order_id','product_id','grade_id','uom_id','sale_quantity',
                'delivered_quantity','remaining_quantity','status','product_attributes','user_note','sale_note','customer_quantity')->get();


        $this->output_json(['data' => $saleorder,'pivot_item' => $pivot_item], 200);

//        $saleorder = SaleOrder::with('factory', 'distributor', 'user','price_list.price_list_items')->where('id', $sale_order_id)->first();
//
//        $pivot_item = SaleOrderItem::with('grade', 'uom', 'product.productstore')->where('sale_order_id', $saleorder->id)->get();
//
//        $this->output_json(['data' => $saleorder, 'pivot_item' => $pivot_item], 200);
    }

    public function checkRemainingQuantityAndStatusSaleOrder($sale_id, SaleOrderRepository $saleOrderRepository) {
        $saleorder = $saleOrderRepository->getOrderAndSale($sale_id);

        if ((is_object($saleorder)) && get_class($saleorder) == Error::class) {
            $this->output_json_client($saleorder, 200);
        }

        $data = $saleOrderRepository->checkRemainingQuantitySaleOrder($saleorder->sale_order_items);

        $check = $data && $saleorder['status'] === SO_CLOSE_STATUS;

        $this->output_json(['data' => $check], 200);
    }


    public function checkRemainingQuantitySaleOrder($sale_id, SaleOrderRepository $saleOrderRepository) {
        $saleorder = $saleOrderRepository->getSaleOrder($sale_id);

        if ((is_object($saleorder)) && get_class($saleorder) == Error::class) {
            $this->output_json_client($saleorder, 200);
        }

        $data = $saleOrderRepository->checkRemainingQuantitySaleOrder($saleorder->sale_order_items);

        $this->output_json(['data' => $data], 200);
    }



    public function closeSaleOrderByAdmin($sale_id, SaleOrderRepository $saleOrderRepository) {
//        $saleorder = $saleOrderRepository->getOrderAndSale($sale_id);
//
//        if ((is_object($saleorder)) && get_class($saleorder) == Error::class) {
//            $this->output_json_client($saleorder, 200);
//        }
//        /*
//         * data  note order
//         * */
//        $changeStatusSaleOrder = $saleOrderRepository->changeStatusSaleToClose($saleorder, $this->data['note']);
//
//        if ((is_object($changeStatusSaleOrder)) && get_class($changeStatusSaleOrder) == Error::class) {
//            $this->output_json_client($changeStatusSaleOrder, 200);
//        }
//        $this->output_json(['data' => $changeStatusSaleOrder], 200);
        $saleorder = $saleOrderRepository->getSaleOrder($sale_id);

        if ((is_object($saleorder)) && get_class($saleorder) == Error::class) {
            $this->output_json_client($saleorder, 200);
        }

        $changeStatusSaleOrder = $saleOrderRepository->changeStatusSaleToClose($saleorder);

        if ((is_object($changeStatusSaleOrder)) && get_class($changeStatusSaleOrder) == Error::class) {
            $this->output_json_client($changeStatusSaleOrder, 200);
        }

        $this->output_json(['data' => $changeStatusSaleOrder], 200);
    }

    public function countSaleOrderAboutDeliveryNote($sale_order_id) {
        $data = DeliveryNoteItem::where('so_id', $sale_order_id)->where('revert_from_item_id', null)->count();
        $this->output_json(['data' => $data], 200);
    }

    public function createSaleOrderAdmin(SaleOrder $saleOrder) {
        if (is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items']);
            $this->data['items'] = array_map(function ($item) {
                if (is_object($item) && get_class($item) === \stdClass::class) {
                    $item = json_decode(json_encode($item), true);
                }
                return $item;
            }, $this->data['items']);
            $this->request->replace($this->data);
        }

        if(is_string($this->data['item_orders'])) {
            $this->data['item_orders'] = json_decode($this->data['item_orders']);

            $this->data['item_orders'] = array_map(function ($item) {
                if (is_object($item) && get_class($item) === \stdClass::class) {
                    $item = json_decode(json_encode($item), true);
                }
                return $item;
            }, $this->data['item_orders']);

            $this->request->replace($this->data);
        }

        if (is_array($this->data['items']) && count($this->data['items']) > 0) {
            $this->data['items'] = array_map(function ($item) {
                if (!isset($item['user_note'])) {
                    $item['user_note'] = '';
                }
                $newSaleOrderItem = new SaleOrderItem([
                    'product_id' => $item['product_id'],
                    'grade_id' => $item['grade_id'],
                    'uom_id' => $item['uom_id'],
                    'sale_quantity' => $item['sale_quantity'],
                    'delivered_quantity' => null,
                    'remaining_quantity' => $item['sale_quantity'],
                    'unit_price_id' => $item['unit_price_id'],
                    'amount' => $item['amount'],
                    'status' => $item['status'],
                    'user_note' => $item['user_note'],
                    'sale_note' => $item['sale_note'],
                    'product_attributes' => json_encode($item['product_attributes']),
                    'unit_price' => $item['unit_price'],
                    'customer_quantity' => $item['sale_quantity'],
                    'code' => $item['code'],
                    'code_stock_order_product' => json_encode($item['dataCheckAmount'])
                ]);
                return $newSaleOrderItem;
            }, $this->data['items']);
        }


        if(is_array($this->data['item_orders']) && count($this->data['item_orders']) > 0) {
            $this->data['item_orders'] = array_map(function ($item) {
                if (!isset($item['user_note'])) {
                    $item['user_note'] = '';
                }
                $newOrderItem = new OrderProduct([
                    'product_id' => $item['product_id'],
                    'amount' => $item['sale_quantity'],
                    'code' => $item['code'],
                    'user_note' => $item['user_note'],
                    'sale_note' => $item['sale_note'],
                    'factory_id' => $item['factory_id'],
                    'grade_id' => $item['grade_id'],
                    'uom_id' => $item['uom_id'],
                    'sale_quantity' => $item['sale_quantity'],
                    'delivered_quantity' => null,
                    'remaining_quantity' => $item['sale_quantity'],
                    'distributor_id' => $item['distributor_id'],
                    'sale_uom' => $item['uom_id'],
                    'status' => $item['status'],
                    'product_attributes' => json_encode($item['product_attributes']),
                    'order_quantity' => $item['sale_quantity'],
                    'code_stock_order_product' => json_encode($item['dataCheckAmount'])
                ]);
                return $newOrderItem;
            }, $this->data['item_orders']);
        }

        $saleOrder = $saleOrder->toModel($this->data);

        if ((isset($this->data['items']) && is_array($this->data['items'])) && (isset($this->data['item_orders']) && is_array($this->data['item_orders']))) {
            $items = array_get($this->data, 'items', []);
            $order_items = array_get($this->data, 'item_orders', []);
            $result = $saleOrder->createSaleOrderSubmitAdmin($items, $order_items);
        } else {
            $result = $saleOrder->createSaleOrderSubmitAdmin();
        }
        $this->output_json($result, 200);
    }
}
