import {OrderProductSelected, ProductSelected} from '../product.state';
import {Product} from '../models/Product';

export interface Pagination {
    page: number;
    perPage: number;
    includeTotalCount: boolean;
}

export interface ReceivedProductsForOrderPayload {
    orderId: null | string;
    products: ProductSelected[];
}
export class GetProducts {
    static readonly type = '[Product] Get Products';
    constructor(public paginationProducts: Pagination) {}
}

export class ReceivedProductsForOrder {
    static readonly type = '[Product] Received Products For Order';
    constructor(public payload: ReceivedProductsForOrderPayload) {}
}

export interface AddProductToOrderPayload {
    orderId: null | string;
    productId: string;
    factoryId: string;
    amount: number;
    code: string;
    gradeId: string;
    attributeId: string;
    distributorId: number;
    uomId: number;
    userNote?: string;
    saleNote?: string;
    statusItem?: number;
    uomName?: string;
    dataCheckAmount?: any;
}
export class AddProductToOrder {
    static readonly type = '[Product] Add Product To Order';
    constructor(public payload: AddProductToOrderPayload) {}
}

export class AddProductsToOrder {
    static readonly type = '[Product] Add Products To Order';
    constructor(public payload: AddProductToOrderPayload[]) {}
}

export class ReceivedProducts {
    static readonly type = '[Product] Received Products';
    constructor(public payload: { [key: string]: Product}) {}
}

export interface RemoveProductFromOrderPayload {
    orderId: null | string;
    code: string;
}
export class RemoveSelectedProductFromOrder {
    static readonly type = '[Product] Remove Selected Product From Order';
    constructor(public payload: RemoveProductFromOrderPayload) {}
}

export interface RemoveAllProductsSelectedFromOrderPayload {
    orderId: null | string;
}
export class RemoveAllSelectedProductFromOrder {
    static readonly type = '[Product] Remove All Selected Products From Order';
    constructor(public payload: RemoveAllProductsSelectedFromOrderPayload) {}
}

export interface ChangeAmountProductFromOrderPayload {
    orderId: null | string;
    code: string;
    amount: number;
}
export class ChangeAmountProductFromOrder {
    static readonly type = '[Product] Change Amount Product From Order';
    constructor(public payload: ChangeAmountProductFromOrderPayload) {}
}

export interface NoteProductFromOrderPayload {
    orderId: null | string;
    code: string;
    userNote: string;
}
export class NoteProductFromOrder {
    static readonly type = '[Product] Note Product From Order';
    constructor(public payload: NoteProductFromOrderPayload) {}
}

export interface ChangeStatusProductFromOrderPayload {
    orderId: null | string;
    code: string;
    status: number;
}
export class ChangeStatusProductFromOrder {
    static readonly type = '[Product] Change Status Product From Order';
    constructor(public payload: ChangeStatusProductFromOrderPayload) {}
}

export interface CheckProductAmountPayload {
    orderId: null | string;
    code: string;
    amount: number;
    dataAmount: any;
}
export class CheckProductAmount {
    static readonly type = '[Product] Check Product Amount';
    constructor(public payload: CheckProductAmountPayload) {}
}

export interface ResetLoadingCheckProductPayload {
    orderId: null | string;
    productId: string;
}
export class ResetLoadingCheckProduct {
    static readonly type = '[Product] Reset Loading Check Product';
    constructor(public payload: ResetLoadingCheckProductPayload) {}
}

export interface SnapshotProductInOrderPayload {
    orderId: string;
}

export class SnapshotProductInOrder {
    static readonly type = '[Product] Snapshot Products in Order';
    constructor(public payload: SnapshotProductInOrderPayload) {}
}

export interface RecoverProductInOrderPayload {
    orderId: string;
    dataCheckAmount: any;
}

export class RecoverProductInOrder {
    static readonly type = '[Product] Recover Products in Order';
    constructor(public payload: RecoverProductInOrderPayload) {}
}

export interface CheckAllAmountProductsInOrderPayload {
    orderId: string;
    dataCheckAmount: any;
}

export class CheckAllAmountProductsInOrder {
    static readonly type = '[Product] Check All Amount Products in Order';
    constructor(public payload: CheckAllAmountProductsInOrderPayload) {}
}
