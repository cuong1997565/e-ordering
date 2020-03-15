<?php
namespace App\Http\Context;

use App\Helpers\Util;
use App\Models\Error;
use Illuminate\Http\Request;

class Context {
    /**
     * Context constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->data = $request->input();
    }

    public function handleEtag($etag) {
        $et = $this->request->header(HEADER_ETAG_CLIENT);
        if ($et && strlen($et) > 0) {
            if ($et == $etag) {
                header(HEADER_ETAG_SERVER.':'.$etag); //HTTP 1.0
                return true;
            }
        }
        return false;
    }
    public function requireCustomerId() {
        if (!isset($this->data['user_id'])) {
            return Error::NewInvalidUrlParamError('user_id');
        }
        if ($this->data['user_id'] == ME) {
            $this->request->merge(['user_id' => $this->request->curSession->customer_id]);
        }
        return true;
    }

    public function requireOrderId() {
        if (!isset($this->data['order_id'])) {
            return Error::NewInvalidUrlParamError('order_id');
        }
        return true;
    }

    public function requireEmailOrUserName() {
        if (!isset($this->data['emailOrusrname'])) {
            return Error::NewInvalidUrlParamError('email or username');
        }
        return true;
    }

    public function requirePassword() {
        if (!isset($this->data['password'])) {
            return Error::NewInvalidUrlParamError('password');
        }
        return true;
    }

    public function requireFactoryId() {
        if (!isset($this->data['factory_id'])) {
            return Error::NewInvalidUrlParamError('factory_id');
        }

        if (filter_var($this->data['factory_id'], FILTER_VALIDATE_INT) === false) {
            return Error::NewInvalidUrlParamError('factory_id');
        }

        if ($this->data['factory_id'] <= 0) {
            return Error::NewInvalidUrlParamError('factory_id');
        }

        return true;
    }

    public function requireProductAmount() {
        if (!isset($this->data['product_amount'])) {
            return Error::NewInvalidUrlParamError('product_amount');
        }

        if (filter_var($this->data['product_amount'], FILTER_VALIDATE_INT) === false) {
            return Error::NewInvalidUrlParamError('product_amount');
        }

        if ($this->data['product_amount'] <= 0) {
            return Error::NewInvalidUrlParamError('product_amount');
        }

        return true;
    }

    public function requireSaleOrderInfoForCreateDeliveryNote() {
        if (!isset($this->data['from_sale_order_items'] ) || empty($this->data['from_sale_order_items'])) {
            return Error::NewAppError('context.delivery_note.from_sale_order_items.empty', 'Context.DeliveryNote.requireSaleOrderInfoForCreateDeliveryNote', '', 'empty data', StatusBadRequest);
        }
        $data = $this->data['from_sale_order_items'];

        foreach ($data as $item) {
            if (!$item['so_item_id'] || !Util::validateInteger($item['so_item_id'])) {
                return Error::NewAppError('context.delivery_note.so_item_id.invalid', 'Context.DeliveryNote.requireSaleOrderInfoForCreateDeliveryNote', '', 'invalid so_item_id', StatusBadRequest);
            }
            if (!$item['so_item_quantity']) {
                return Error::NewAppError('context.delivery_note.so_item_quantity.invalid', 'Context.DeliveryNote.requireSaleOrderInfoForCreateDeliveryNote', '', 'invalid so_item_quantity', StatusBadRequest);
            }
        }

        return true;
    }

    public function setRequest(Request $request) {
        $this->request = $request;
        $this->data = $request->input();
    }
}
