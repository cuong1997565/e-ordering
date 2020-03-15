import {CreateDraftOrderRequest, Order, UpdateOrderRequest, CreateOrderRequest, UpdateDraftOrderRequest} from '../models/Order';

export class InitOrder {
    static readonly type = '[Init] Order';
}

export class CreateDraftOrder {
    static readonly type = '[Order] Create Draft Order';
    constructor(public payload: CreateDraftOrderRequest) {}
}

export class UpdateDraftOrder {
    static readonly type = '[Order] Update Draft Order';
    constructor(public payload: UpdateDraftOrderRequest) {}
}

export class CreateOrder {
    static readonly type = '[Order] Create  Order';
    constructor(public payload: CreateOrderRequest) {}
}

export class UpdateOrder {
    static readonly type = '[Order] Update Order';
    constructor(public payload: UpdateOrderRequest) {}
}

export interface GetOrderPayload {
    orderId: null | string;
}

export class GetOrder {
    static readonly type = '[Order] Get Order';
    constructor(public payload: GetOrderPayload) {}
}

export interface ReceivedOrderPayload {
    id: string | null;
}
export class ReceivedOrder {
    static readonly type = '[Order] Received order';
    constructor(public payload: ReceivedOrderPayload) {}
}

export class ReceivedOrders {
    static readonly type = '[Order] Received orders';
    constructor(public payload: { [key: string]: Order}) {}
}

export class RemoveCurrentOrderId {
    static readonly type = '[Order] Remove Current Order Id';
    constructor() {}
}
