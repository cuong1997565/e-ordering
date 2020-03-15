<?php

namespace App\Http\Controllers;

use App\App\CustomerRepository;
use App\App\DistributorRepository;
use App\Http\Context\Context;
use App\Models\Error;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use App\Models\Uom;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Distributor;
use App\App\OrderRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrdersController extends Controller
{

    public function getClientOrder($order_id, OrderRepository $or, Context $context)
    {
        $this->request->merge(['order_id' => $order_id]);
        $context->setRequest($this->request);
        $validate = $context->requireOrderId();
        $context->setRequest($this->request);
        if ((is_object($validate)) && get_class($validate) == Error::class) {
            $this->output_json_client($validate, 200);
        }

        $order = $or->getOrder($order_id);
        $this->output_json_client($order, 200);
    }

    public function reviewCustomerOrderByAdmin($order_id, OrderRepository $or)
    {
        if (is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items']);
            $this->data['items'] = array_map(function ($item) {
                if (is_object($item) && get_class($item) === \stdClass::class) {
                    $item = json_decode(json_encode($item), true);
                }
                return $item;
            }, $this->data['items']);
        }

        $order = $or->getOrder($order_id);
        if ((is_object($order)) && get_class($order) == Error::class) {
            $this->output_json_client($order, 200);
        }

        if (is_array($this->data['items']) && count($this->data['items']) > 0) {
            $this->data['items'] = array_map(function ($item) {
                if (!isset($item['user_note'])) {
                    $item['user_note'] = '';
                }
                $newOrderProduct = new OrderProduct([
                    'product_id' => $item['product_id'],
                    'amount' => $item['amount'],
                    'code' => $item['code'],
                    'user_note' => $item['user_note'],
                    'sale_note' => $item['sale_note'],
                    'factory_id' => $item['factory_id'],
                    'grade_id' => $item['grade_id'],
                    'distributor_id' => $item['distributor_id'],
                    'product_attributes' => json_encode( $item['product_attributes']),
                    'uom_id' => (int)$item['uom_id'],
                    'sale_uom' => $item['sale_uom'],
                    'order_quantity' => $item['order_quantity'],
                    'status' => $item['status'],
                    'code_stock_order_product' => json_encode( $item['code_stock_order_product']),
                ]);
                return $newOrderProduct;
            }, $this->data['items']);
        }


        $items = $this->data['items'];

        $order->note = $this->data['note'];

        $newOrder = $or->changeStatusOrderToProcessing($order, $items);

        if (is_object($newOrder) && get_class($newOrder) == Error::class) {
            $this->output_status_fail($newOrder);
        } else {
            $this->output_json_client('OK', 200);
        }
    }

    public function approveCustomerOrderByAdmin($order_id, OrderRepository $or)
    {
        if (is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items']);
            $this->data['items'] = array_map(function ($item) {
                if (is_object($item) && get_class($item) === \stdClass::class) {
                    $item = json_decode(json_encode($item), true);
                }
                return $item;
            }, $this->data['items']);
        }

        $order = $or->getOrder($order_id);

        if ((is_object($order)) && get_class($order) == Error::class) {
            $this->output_json_client($order, 200);
        }

        if (is_array($this->data['items']) && count($this->data['items']) > 0) {
            $this->data['items'] = array_map(function ($item) {
                if (!isset($item['user_note'])) {
                    $item['user_note'] = '';
                }
                $newOrderProduct = new OrderProduct([
                    'product_id' => $item['product_id'],
                    'amount' => $item['amount'],
                    'code' => $item['code'],
                    'user_note' => $item['user_note'],
                    'sale_note' => $item['sale_note'],
                    'factory_id' => $item['factory_id'],
                    'grade_id' => $item['grade_id'],
                    'distributor_id' => $item['distributor_id'],
                    'product_attributes' => json_encode( $item['product_attributes']),
                    'uom_id' => (int)$item['uom_id'],
                    'sale_uom' => $item['sale_uom'],
                    'order_quantity' => $item['order_quantity'],
                    'status' => $item['status'],
                    'code_stock_order_product' => json_encode($item['code_stock_order_product'])
                ]);
                return $newOrderProduct;
            }, $this->data['items']);
        }

        $items = $this->data['items'];

        $order->note = $this->data['note'];


        $newOrder = $or->changeStatusOrderToApproved($order, $items);
        if (is_object($newOrder) && get_class($newOrder) == Error::class) {
            $this->output_status_fail($newOrder);
        } else {
            $this->output_json_client($newOrder, 200);
        }
    }

    public function rejectCustomerOrderByAdmin($order_id, OrderRepository $or)
    {
        if (is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items']);
            $this->data['items'] = array_map(function ($item) {
                if (is_object($item) && get_class($item) === \stdClass::class) {
                    $item = json_decode(json_encode($item), true);
                }
                return $item;
            }, $this->data['items']);
        }

        $order = $or->getOrder($order_id);

        if ((is_object($order)) && get_class($order) == Error::class) {
            $this->output_json_client($order, 200);
        }

        if (is_array($this->data['items']) && count($this->data['items']) > 0) {
            $this->data['items'] = array_map(function ($item) {
                if (!isset($item['user_note'])) {
                    $item['user_note'] = '';
                }
                $newOrderProduct = new OrderProduct([
                    'product_id' => $item['product_id'],
                    'amount' => $item['amount'],
                    'code' => $item['code'],
                    'user_note' => $item['user_note'],
                    'sale_note' => $item['sale_note'],
                    'factory_id' => $item['factory_id'],
                    'grade_id' => $item['grade_id'],
                    'distributor_id' => $item['distributor_id'],
                    'product_attributes' => json_encode($item['product_attributes']),
                    'uom_id' => (int)$item['uom_id'],
                    'sale_uom' => $item['sale_uom'],
                    'order_quantity' => $item['order_quantity'],
                    'status' => $item['status'],
                    'code_stock_order_product' => json_encode($item['code_stock_order_product'])
                ]);
                return $newOrderProduct;
            }, $this->data['items']);
        }

        $items = $this->data['items'];

        $order->note = $this->data['note'];

        $newOrder = $or->changeStatusOrderToRejected($order, $items);

        if (is_object($newOrder) && get_class($newOrder) == Error::class) {
            $this->output_status_fail($newOrder);
        } else {
            $this->output_json_client('OK', 200);
        }
    }


    public function getAllCustomerOrdersForAdmin()
    {
        $order = Order::with(['factory' => function ($q) {
            $q->select('id', 'name');
        },
            'customer' => function ($u) {
                $u->select('id', 'email', 'name');
            }])->where('status', '!=', WAITING_FOR_DRAF_ORDER)->getDynamic();

        $this->output_json(['data' => $order], 200);
    }

    public function getClientOrders()
    {
        $area = Order::with(['factory' => function ($q) {
            $q->select('id', 'name');
        },
            'customer' => function ($u) {
                $u->select('id', 'email', 'name');
            }])->getDynamic();

        $this->output_json(['data' => $area], 200);
    }

    public function getOrderDetailAdmin()
    {
        $order = Order::with('distributor.area', 'factory', 'customer', 'products')->getDynamic();

        $pivot_item = OrderProduct::with('grade', 'uom', 'product')->where('order_id', $order[0]->id)->get();

        $this->output_json(['data' => $order, 'pivot_item' => $pivot_item], 200);
    }

    public function getClientSreachOrders(OrderRepository $order)
    {
        $order_code = isset($_GET['order_code']) ? $_GET['order_code'] : null;

        $factory_id = isset($_GET['factory_id']) ? $_GET['factory_id'] : null;

        $status = isset($_GET['status']) ? $_GET['status'] : null;

        $from_date1 = isset($_GET['from_date1']) ? $_GET['from_date1'] : null;

        $from_date2 = isset($_GET['from_date2']) ? $_GET['from_date2'] : null;

        $to_date1 = isset($_GET['to_date1']) ? $_GET['to_date1'] : null;

        $to_date2 = isset($_GET['to_date2']) ? $_GET['to_date2'] : null;

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

        $distributor_id = isset($_GET['distributor_id']) ? $_GET['distributor_id'] : null;

        $data = $order->getOrders($limit, $order_code, $factory_id, $status, $from_date1, $from_date2, $to_date1, $to_date2, $distributor_id);


        $this->output_json(['data' => $data], 200);

    }

    public function createDraftOrder(Order $order, Request $request, CustomerRepository $customerRepository)
    {
        if (is_string($this->data['products'])) {
            $this->data['products'] = json_decode($this->data['products']);
        }


        if (is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items']);
            $this->data['items'] = array_map(function ($item) {
                if (is_object($item) && get_class($item) === \stdClass::class) {
                    $item = json_decode(json_encode($item), true);
                }
                return $item;
            }, $this->data['items']);
        }

        if (is_array($this->data['items']) && empty($this->data['items'])) {
            unset($this->data['items']);
        }
        if (is_array($this->data['products']) && empty($this->data['products'])) {
            unset($this->data['products']);
        }


        $this->data['status'] = WAITING_FOR_DRAF_ORDER;

        $this->request->replace($this->data);

        $this->validateAmountDistributorProduct();

        $this->validate($this->request,
            [
                'factory_id' => 'required|integer',
                'creator_id' => 'required|integer',
                'code' => 'required|unique:orders,code',
//                'items.*.amount' => 'check_min_quantity'
//                'creator_note' => 'required',
//                'deliver_date' => 'required',
//                'deliver_actual' => 'required',
//                'note' => 'required',
            ],
            [
                'factory_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'factory']),
                'factory_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'factory id']),
                'creator_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'creator']),
                'creator_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'creator id']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
//                'items.*.amount.check_min_quantity' => trans('messages.api.min_amount.distributor.app_error'),
                'creator_note.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'creator note']),
                'deliver_date.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver date']),
                'deliver_actual.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver actual']),
                'note.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'note'])
            ]);

        if (is_array($this->data['items']) && count($this->data['items']) > 0) {
            $this->data['items'] = array_map(function ($item) {
                if (!isset($item['user_note'])) {
                    $item['user_note'] = '';
                }
                $uom = Uom::find($item['uom_id']);
                $item['sale_uom'] = '';
                $item['order_quantity']  = '';
                if($uom) {
                    $item['sale_uom'] = $item['uom_id'];
                    if($uom['is_based_uom'] == IS_BASED_UOM_FALSE) {
                         $item['sale_uom'] = $uom['based_uom_id'];
                         $item['order_quantity'] = $uom['conversion_rate'] * $item['amount'];
                    } else {
                         $item['order_quantity'] = $item['amount'];
                    }
                }
                // create code check amount product
                $arrayCheckAmount = $item['dataCheckAmount'];
                $data = $arrayCheckAmount[0] . $arrayCheckAmount[1] . $arrayCheckAmount[2] . $arrayCheckAmount[9] . '64F0F1368c68D97A7885B880F365C6B8';
                $code = md5($data);
                $arrayCheckAmount[10] = $code;
                $newOrderProduct = new OrderProduct([
                    'product_id' => $item['product_id'],
                    'amount' => $item['amount'],
                    'code' => $item['code'],
                    'user_note' => $item['user_note'],
                    'factory_id' => $item['factory_id'],
                    'grade_id' => $item['grade_id'],
                    'distributor_id' => $item['distributor_id'],
                    'product_attributes' => json_encode( $item['attributes']),
                    'uom_id' => (int)$item['uom_id'],
                    'sale_uom' => $item['sale_uom'],
                    'order_quantity' => $item['order_quantity'],
                    'status' => WAITING_FOR_DRAF_ITEM_ORDER,
                    'code_stock_order_product' => json_encode( $arrayCheckAmount)
                ]);
                return $newOrderProduct;
            }, $this->data['items']);
        }
        $order = $order->toModel($this->data);
        $order->items = $this->data['items'];

        $data_account_holder = $customerRepository->getCustomerAccountHolder($order['distributor_id']);

        $order['creator_id'] = $data_account_holder['id'];


        if ((isset($this->data['products']) && is_array($this->data['products']))) {
            $products = array_get($this->data, 'products', []);
            $products = array_map(function ($product) {
                if (is_object($product) && get_class($product) === \stdClass::class) {
                    $product = json_decode(json_encode($product), true);
                }
                return $product;
            }, $products);
            $result = $order->createOrder($products);
        } else {
            $result = $order->createOrder();
        }
        $this->output_json($result, 200);
    }

    /*
     * validator min max amount
     * */
    public function validateAmountDistributorProduct() {
        $item = array();
        foreach ($this->data['items'] as $key => $value) {
            $amountProduct = (new DistributorRepository())->productGetDistributor($value['product_id'], $value['distributor_id']);
            if(count($amountProduct) > 0) {
                if($value['amount'] < $amountProduct[0]['min_quantity'] || $value['amount'] > $amountProduct[0]['max_quantity'] ) {
                    array_push($item, array(
                        'key' => $key,
                        'quantity_min' => $amountProduct[0]['min_quantity'],
                        'message' => trans('messages.api.amount.distributor.app_error',
                            ['Min' => $amountProduct[0]['min_quantity'],'Max' => $amountProduct[0]['max_quantity']]),
                        'StatusCode' => 422,
                        'Where' =>  "OrdersController:validateAmountDistributorProduct"
                    ));
                }
            }
        }
        if(count($item) > 0) {
            $data = json_encode($item);
            $error = Error::NewAppError($data,
                'OrdersController.validateAmountDistributorProduct', null, null, 422);
            $this->output_json_client($error, 400);
        }
    }

    public function createOrderClient(Order $order, Request $request, CustomerRepository $customerRepository)
    {
        if (is_string($this->data['products'])) {
            $this->data['products'] = json_decode($this->data['products']);
        }

        if (is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items']);
            $this->data['items'] = array_map(function ($item) {
                if (is_object($item) && get_class($item) === \stdClass::class) {
                    $item = json_decode(json_encode($item), true);
                }
                return $item;
            }, $this->data['items']);
        }

        if (is_array($this->data['items']) && empty($this->data['items'])) {
            unset($this->data['items']);
        }
        if (is_array($this->data['products']) && empty($this->data['products'])) {
            unset($this->data['products']);
        }

        $this->data['status'] = SUBMITED_ORDER;

        $this->request->replace($this->data);

        $this->validateAmountDistributorProduct();


        $this->validate($this->request,
            [
                'factory_id' => 'required|integer',
                'creator_id' => 'required|integer',
                'code' => 'required|unique:orders,code',
//                'creator_note' => 'required',
//                'deliver_date' => 'required',
//                'deliver_actual' => 'required',
//                'note' => 'required',
            ],
            [
                'factory_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'factory']),
                'factory_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'factory id']),
                'creator_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'creator']),
                'creator_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'creator id']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'creator_note.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'creator note']),
                'deliver_date.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver date']),
                'deliver_actual.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver actual']),
                'note.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'note'])
            ]);

        if (is_array($this->data['items']) && count($this->data['items']) > 0) {
            $this->data['items'] = array_map(function ($item) {
                if (!isset($item['user_note'])) {
                    $item['user_note'] = '';
                }
                $uom = Uom::find($item['uom_id']);
                if($uom) {
                    $item['sale_uom'] = $item['uom_id'];
                    if($uom['is_based_uom'] == IS_BASED_UOM_FALSE) {
                        $item['sale_uom'] = $uom['based_uom_id'];
                        $item['order_quantity'] = $uom['conversion_rate'] * $item['amount'];
                    } else {
                        $item['order_quantity'] = $item['amount'];
                    }
                }

                // create code check amount product
                $arrayCheckAmount = $item['dataCheckAmount'];
                $data = $arrayCheckAmount[0] . $arrayCheckAmount[1] . $arrayCheckAmount[2] . $arrayCheckAmount[9] . '64F0F1368c68D97A7885B880F365C6B8';
                $code = md5($data);
                $arrayCheckAmount[10] = $code;

                $newOrderProduct = new OrderProduct([
                    'product_id' => $item['product_id'],
                    'amount' => $item['amount'],
                    'code' => $item['code'],
                    'user_note' => $item['user_note'],
                    'factory_id' => $item['factory_id'],
                    'grade_id' => $item['grade_id'],
                    'distributor_id'  => $item['distributor_id'],
                    'product_attributes' => json_encode($item['attributes']),
                    'uom_id' => $item['uom_id'],
                    'sale_uom' => $item['sale_uom'],
                    'order_quantity' => $item['order_quantity'],
                    'status' => Accept_ITEM_ORDER,
                    'code_stock_order_product' => json_encode( $arrayCheckAmount)

                ]);
                return $newOrderProduct;
            }, $this->data['items']);
        }
        $order = $order->toModel($this->data);
        $order->items = $this->data['items'];

        $data_account_holder = $customerRepository->getCustomerAccountHolder($order['distributor_id']);

        $order['creator_id'] = $data_account_holder['id'];

        if ((isset($this->data['products']) && is_array($this->data['products']))) {
            $products = array_get($this->data, 'products', []);
            $products = array_map(function ($product) {
                if (is_object($product) && get_class($product) === \stdClass::class) {
                    $product = json_decode(json_encode($product), true);
                }
                return $product;
            }, $products);
            $result = $order->createOrder($products);
        } else {
            $result = $order->createOrder();
        }
        $this->output_json($result, 200);
    }

    public function createOrders(Order $order, Request $request)
    {
        if (is_string($this->data['products'])) {
            $this->data['products'] = json_decode($this->data['products']);
        }

        if (is_array($this->data['products']) && empty($this->data['products'])) {
            unset($this->data['products']);
        }

        $this->request->replace($this->data);

        $this->validate($this->request,
            [
                'factory_id' => 'required|integer',
                'creator_id' => 'required|integer',
                'code' => 'required|unique:orders,code',
//                'status' => 'required',
//                'creator_note' => 'required',
//                'deliver_date' => 'required',
//                'deliver_actual' => 'required',
//                'note' => 'required',
            ],
            [
                'factory_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'factory']),
                'factory_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'factory id']),
                'creator_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'creator']),
                'creator_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'creator id']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'status.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'status']),
                'creator_note.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'creator note']),
                'deliver_date.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver date']),
                'deliver_actual.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver actual']),
                'note.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'note'])
            ]);
        $this->data['status'] =
        $order = $order->toModel($this->data);

        if ((isset($this->data['products']) && is_array($this->data['products']))) {
            $products = array_get($this->data, 'products', []);
            $result = $order->createOrder($products);
        } else {
            $result = $order->createOrder();
        }
        $this->output_json($result, 200);
    }

    public function updateOrderDrafClient($order_id, Order $order, Request $request, CustomerRepository $customerRepository)
    {
        if (is_string($this->data['products'])) {
            $this->data['products'] = json_decode($this->data['products']);
        }

        if (is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items']);
            $this->data['items'] = array_map(function ($item) {
                if (is_object($item) && get_class($item) === \stdClass::class) {
                    $item = json_decode(json_encode($item), true);
                }
                return $item;
            }, $this->data['items']);
        }

        if (is_array($this->data['items']) && empty($this->data['items'])) {
            unset($this->data['items']);
        }

        if (is_array($this->data['products']) && empty($this->data['products'])) {
            unset($this->data['products']);
        }

        $request_validate = clone $this->request;

        $request_validate->replace($this->data);

        $this->validateAmountDistributorProduct();

        $this->validate($request_validate,
            [
                'factory_id' => 'required|integer',
                'creator_id' => 'required|integer',
                'code' => 'required|unique:orders,code,' . $order_id,
//                'status' => 'required',
//                'creator_note' => 'required',
//                'deliver_date' => 'required',
//                'deliver_actual' => 'required',
//                'note' => 'required',
            ],
            [
                'factory_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'factory']),
                'factory_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'factory id']),
                'creator_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'creator']),
                'creator_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'creator id']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'status.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'status']),
                'creator_note.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'creator note']),
                'deliver_date.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver date']),
                'deliver_actual.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver actual']),
                'note.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'note'])
            ]);

        $this->data['id'] = $order_id;
        if (is_array($this->data['items']) && count($this->data['items']) > 0) {
            $order = Order::find($order_id);
            $item_order['status'] = WAITING_FOR_DRAF_ITEM_ORDER;
            if($order) {
                if($order['status'] === SUBMITED_ORDER) {
                    $item_order['status'] = Accept_ITEM_ORDER;
                }
            }
            $this->data['items'] = array_map(function ($item) use ($item_order) {
                if (!isset($item['user_note'])) {
                    $item['user_note'] = '';
                }
                $uom = Uom::find($item['uom_id']);
                if($uom) {
                    $item['sale_uom'] = $item['uom_id'];
                    if($uom['is_based_uom'] == IS_BASED_UOM_FALSE) {
                        $item['sale_uom'] = $uom['based_uom_id'];
                        $item['order_quantity'] = $uom['conversion_rate'] * $item['amount'];
                    } else {
                        $item['order_quantity'] = $item['amount'];
                    }
                }

                // create code check amount product
                $arrayCheckAmount = $item['dataCheckAmount'];
                $data = $arrayCheckAmount[0] . $arrayCheckAmount[1] . $arrayCheckAmount[2] . $arrayCheckAmount[9] . '64F0F1368c68D97A7885B880F365C6B8';
                $code = md5($data);
                $arrayCheckAmount[10] = $code;

                $newOrderProduct = new OrderProduct([
                    'product_id' => $item['product_id'],
                    'amount' => $item['amount'],
                    'code' => $item['code'],
                    'user_note' => $item['user_note'],
                    'factory_id' => $item['factory_id'],
                    'grade_id' => $item['grade_id'],
                    'distributor_id' => $item['distributor_id'],
                    'product_attributes' => json_encode($item['attributes']),
                    'uom_id' => $item['uom_id'],
                    'sale_uom' => $item['sale_uom'],
                    'order_quantity' => $item['order_quantity'],
                    'status' => $item_order['status'],
                    'code_stock_order_product' => json_encode( $arrayCheckAmount)
                ]);
                return $newOrderProduct;
            }, $this->data['items']);
        }

        $order = $order->toModel($this->data);

        $data_account_holder = $customerRepository->getCustomerAccountHolder($order['distributor_id']);

        $order['creator_id'] = $data_account_holder['id'];

        $remain = sprintf("%03d", $order_id % 999);
        $time = Carbon::now()->format('y') . Carbon::now()->format('m') .Carbon::now()->format('d');
        $order->po_number = 'PO-PRIME-'.$order->distributor->code.'-'.$time.'-'.$remain;
        $order->items = $this->data['items'];
        //check status order
        $order_update = Order::find($order_id);
        if ($order_update && ($order_update['status'] === WAITING_FOR_CONFIRM_ORDER ||
                $order_update['status'] === SUBMITED_ORDER ||
                $order_update['status'] === WAITING_FOR_DRAF_ORDER)) {
            if ((isset($this->data['products']) && is_array($this->data['products']))) {
                $products = array_get($this->data, 'products', []);
                $products = array_map(function ($product) {
                    if (is_object($product) && get_class($product) === \stdClass::class) {
                        $product = json_decode(json_encode($product), true);
                    }
                    return $product;
                }, $products);

                $result = $order->updateDrafOrder($products);
            } else {
                $result = $order->updateDrafOrder();
            }

            $this->output_json($result, 200);
        } else {
            $result = 'Can not update order';
            $this->output_json($result, 400);
        }
    }

    public function updateOrderClient($order_id, Order $order, Request $request, CustomerRepository $customerRepository)
    {
        if (is_string($this->data['products'])) {
            $this->data['products'] = json_decode($this->data['products']);
        }

        if (is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items']);
            $this->data['items'] = array_map(function ($item) {
                if (is_object($item) && get_class($item) === \stdClass::class) {
                    $item = json_decode(json_encode($item), true);
                }
                return $item;
            }, $this->data['items']);
        }

        if (is_array($this->data['items']) && empty($this->data['items'])) {
            unset($this->data['items']);
        }

        if (is_array($this->data['products']) && empty($this->data['products'])) {
            unset($this->data['products']);
        }

        $request_validate = clone $this->request;

        $request_validate->replace($this->data);

        $this->validateAmountDistributorProduct();

        $this->validate($request_validate,
            [
                'factory_id' => 'required|integer',
                'creator_id' => 'required|integer',
                'code' => 'required|unique:orders,code,' . $order_id,
//                'status' => 'required',
//                'creator_note' => 'required',
//                'deliver_date' => 'required',
//                'deliver_actual' => 'required',
//                'note' => 'required',
            ],
            [
                'factory_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'factory']),
                'factory_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'factory id']),
                'creator_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'creator']),
                'creator_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'creator id']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'status.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'status']),
                'creator_note.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'creator note']),
                'deliver_date.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver date']),
                'deliver_actual.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver actual']),
                'note.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'note'])
            ]);

        $this->data['id'] = $order_id;
        if (is_array($this->data['items']) && count($this->data['items']) > 0) {
            $this->data['items'] = array_map(function ($item) {
                if (!isset($item['user_note'])) {
                    $item['user_note'] = '';
                }

                $uom = Uom::find($item['uom_id']);
                if($uom) {
                    $item['sale_uom'] = $item['uom_id'];
                    if($uom['is_based_uom'] == IS_BASED_UOM_FALSE) {
                        $item['sale_uom'] = $uom['based_uom_id'];
                        $item['order_quantity'] = $uom['conversion_rate'] * $item['amount'];
                    } else {
                        $item['order_quantity'] = $item['amount'];
                    }
                }

                // create code check amount product
                $arrayCheckAmount = $item['dataCheckAmount'];
                $data = $arrayCheckAmount[0] . $arrayCheckAmount[1] . $arrayCheckAmount[2] . $arrayCheckAmount[9] . '64F0F1368c68D97A7885B880F365C6B8';
                $code = md5($data);
                $arrayCheckAmount[10] = $code;

                $newOrderProduct = new OrderProduct([
                    'product_id' => $item['product_id'],
                    'amount' => $item['amount'],
                    'code' => $item['code'],
                    'user_note' => $item['user_note'],
                    'factory_id' => $item['factory_id'],
                    'grade_id' => $item['grade_id'],
                    'distributor_id' => $item['distributor_id'],
                    'product_attributes' => json_encode($item['attributes']),
                    'uom_id' => $item['uom_id'],
                    'sale_uom' => $item['sale_uom'],
                    'order_quantity' => $item['order_quantity'],
                    'status' => Accept_ITEM_ORDER,
                    'code_stock_order_product' => json_encode( $arrayCheckAmount)

                ]);
                return $newOrderProduct;
            }, $this->data['items']);
        }

        $order = $order->toModel($this->data);
        $order->items = $this->data['items'];

        $data_account_holder = $customerRepository->getCustomerAccountHolder($order['distributor_id']);

        $order['creator_id'] = $data_account_holder['id'];

        $remain = sprintf("%03d", $order_id % 999);
        $time = Carbon::now()->format('y') . Carbon::now()->format('m') .Carbon::now()->format('d');
        $order->po_number = 'PO-PRIME-'.$order->distributor->code.'-'. $time .'-'.$remain;

        //check status order
        $order_update = Order::find($order_id);
        if ($order_update && ($order_update['status'] === WAITING_FOR_CONFIRM_ORDER ||
                $order_update['status'] === SUBMITED_ORDER ||
                $order_update['status'] === WAITING_FOR_DRAF_ORDER)) {
            if ((isset($this->data['products']) && is_array($this->data['products']))) {
                $products = array_get($this->data, 'products', []);
                $products = array_map(function ($product) {
                    if (is_object($product) && get_class($product) === \stdClass::class) {
                        $product = json_decode(json_encode($product), true);
                    }
                    return $product;
                }, $products);

                $result = $order->updateOrderClient($products);
            } else {
                $result = $order->updateOrderClient();
            }

            $this->output_json($result, 200);
        } else {
            $result = 'Can not update order';
            $this->output_json($result, 400);
        }
    }

    public function updateStatusOrder($order_id, Order $order, Request $request)
    {

        $this->data['id'] = $order_id;

        $order_update = Order::find($this->data['id']);
        if ($order_update['status'] === WAITING_FOR_CONFIRM_ORDER || $order_update['status'] === SUBMITED_ORDER) {
            $this->data['status'] = CANCELLED_BY_CUSTOMER_ORDER;

            $this->data['canceled_date'] = date("Y-m-d H:i:s");

            $order = $order->toModel($this->data);

            $result = $order->updateStatusOrder($order);

            $this->output_json($result, 200);
        } else {
            $result = "Can not update order";

            $this->output_json($result, 422);
        }

    }

    public function updateOrderAdmin($order_id, Order $order, Request $request)
    {
        if (is_string($this->data['products'])) {
            $this->data['products'] = json_decode($this->data['products']);
        }

        if (is_string($this->data['items'])) {
            $this->data['items'] = json_decode($this->data['items']);
            $this->data['items'] = array_map(function ($item) {
                if (is_object($item) && get_class($item) === \stdClass::class) {
                    $item = json_decode(json_encode($item), true);
                }
                return $item;
            }, $this->data['items']);
        }
        if (is_array($this->data['items']) && empty($this->data['items'])) {
            unset($this->data['items']);
        }

        if (is_array($this->data['products']) && empty($this->data['products'])) {
            unset($this->data['products']);
        } else {
            if (isset($this->data['products'])) {
                $this->data['products'] = array_map(function ($product) {
                    if (is_object($product) && get_class($product) === \stdClass::class) {
                        $product = json_decode(json_encode($product), true);
                    }
                    return $product;
                }, $this->data['products']);
            }
        }

        $this->request->replace($this->data);
        $this->validate($this->request,
            [
                'products.*.amount' => 'validate_amount',
                'factory_id' => 'required|integer',
                'creator_id' => 'required|integer',
                'code' => 'required|unique:orders,code,' . $order_id,
//                'status' => 'required',
//                'creator_note' => 'required',
//                'deliver_date' => 'required',
//                'deliver_actual' => 'required',
//                'note' => 'required',
            ],
            [
                'factory_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'factory']),
                'factory_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'factory id']),
                'creator_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'creator']),
                'creator_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'creator id']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'status.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'status']),
                'creator_note.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'creator note']),
                'deliver_date.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver date']),
                'deliver_actual.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'deliver actual']),
                'note.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'note'])
            ]);

        $this->data['id'] = $order_id;
        if (is_array($this->data['items']) && count($this->data['items']) > 0) {
            $this->data['items'] = array_map(function ($item) {
                if (!isset($item['user_note'])) {
                    $item['user_note'] = '';
                }

                $newOrderProduct = new OrderProduct([
                    'product_id' => $item['product_id'],
                    'amount' => $item['amount'],
                    'order_quantity' => $item['order_quantity'],
                    'code' => $item['code'],
                    'user_note' => $item['user_note'],
                    'sale_note' => $item['sale_note'],
                    'factory_id' => $item['factory_id'],
                    'grade_id' => $item['grade_id'],
                    'uom_id' => $item['uom_id'],
                    'sale_quantity' => $item['sale_quantity'],
                    'delivery_quantity' => $item['delivery_quantity'],
                    'remaining_quantity' => $item['remaining_quantity'],
                    'distributor_id' => $item['distributor_id'],
                    'product_attributes' => $item['product_attributes'],
                    'sale_uom' => $item['sale_uom'],
                    'code_stock_order_product' => json_encode($item['code_stock_order_product'])

                ]);
                return $newOrderProduct;
            }, $this->data['items']);
        }

        $order = $order->toModel($this->data);
        $order->items = $this->data['items'];



        if ($order->status === SUBMITED_ORDER || WAITING_FOR_CONFIRM_ORDER || REVIEWING_ORDER || SALES_APPROVED_ORDER) {
            if ((isset($this->data['products']) && is_array($this->data['products']))) {
                $products = array_get($this->data, 'products', []);
                $products = array_map(function ($product) {
                    if (is_object($product) && get_class($product) === \stdClass::class) {
                        $product = json_decode(json_encode($product), true);
                    }
                    return $product;
                }, $products);
                $result = $order->updateOrder($products);
            } else {
                $result = $order->updateOrder();
            }
            $this->output_json($result, 200);

        }

    }

    public function deleteOrder($order_id, Order $order)
    {
        Order::find($order_id)->delete();
    }

    public function deleteOrderProduct()
    {
        $this->deleteRecord('OrderProduct');
    }

    public function ChangeStatusAdmin()
    {
        $order = Order::find($this->data['id']);

        if ($order && $order->status === SUBMITED_ORDER) {

            if ((int)$this->data['status'] === REJECT_BY_SALES_ORDER) {
                $order->status = $this->data['status'];
                $order->rejected_date = date("Y-m-d H:i:s");
            }

            if ($order && (int)$this->data['status'] === SALES_APPROVED_ORDER) {
                $order->status = $this->data['status'];
                $order->approved_date = date("Y-m-d H:i:s");
            }

            if ($order && (int)$this->data['status'] === REVIEWING_ORDER) {
                $order->status = $this->data['status'];
                $order->processing_date = date("Y-m-d H:i:s");
            }

            $order->save();
        }

        if ($order) {
            if ($order->status === WAITING_FOR_CONFIRM_ORDER || $order->status === REVIEWING_ORDER) {

                if ((int)$this->data['status'] === REJECT_BY_SALES_ORDER) {
                    $order->status = $this->data['status'];
                    $order->rejected_date = date("Y-m-d H:i:s");
                }

                if ($order && (int)$this->data['status'] === SALES_APPROVED_ORDER) {
                    $order->status = $this->data['status'];
                    $order->approved_date = date("Y-m-d H:i:s");
                }

                $order->save();
            }
        }

        if ($order) {
            if ($order->status === SALES_APPROVED_ORDER || $order->status === DELIVERING_ORDER) {

                if ((int)$this->data['status'] === REJECT_BY_SALES_ORDER) {
                    $order->status = $this->data['status'];
                    $order->rejected_date = date("Y-m-d H:i:s");
                }

                $order->save();
            }
        }

    }

    public function changeStatusProductOrder($order_id, OrderRepository $or)
    {

        $newOrder = $or->changeStatusOrderItemReject($order_id);

        if (is_object($newOrder) && get_class($newOrder) == Error::class) {
            $this->output_status_fail($newOrder);
        } else {
            $this->output_json_client('OK', 200);
        }
    }


    public function closeOrderByAdmin($order_id, OrderRepository $or) {
        /*
         * data  note order
         * */
        $data = array(
        'order_id' => $order_id,
        'note' => $this->data['note']);
        $changeStatusOrderClose = $or->changeStatusOrderToClose($data);

        if ((is_object($changeStatusOrderClose)) && get_class($changeStatusOrderClose) == Error::class) {
            $this->output_json_client($changeStatusOrderClose, 200);
        }
        $this->output_json(['data' => $changeStatusOrderClose], 200);
    }

}
