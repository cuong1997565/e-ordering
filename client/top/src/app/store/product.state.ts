import {Action, createSelector, Selector, State, StateContext, Store} from '@ngxs/store';
import {
    AddProductsToOrder,
    AddProductToOrder,
    AddProductToOrderPayload,
    ChangeAmountProductFromOrder,
    ChangeStatusProductFromOrder, CheckAllAmountProductsInOrder,
    CheckProductAmount,
    GetProducts, NoteProductFromOrder,
    ReceivedProducts,
    ReceivedProductsForOrder,
    ReceivedProductsForOrderPayload, RecoverProductInOrder, RemoveAllProductsSelectedFromOrderPayload,
    RemoveAllSelectedProductFromOrder,
    RemoveSelectedProductFromOrder,
    ResetLoadingCheckProduct,
    SnapshotProductInOrder
} from './actions/products.action';
import {ClientService} from './client/client.service';
import {Product} from './models/Product';
import {constant} from '../config';
import {st} from '@angular/core/src/render3';
import {UserStateModel} from './user.state';
import {OrderState, OrderStateModel} from './order.state';
import {BehaviorSubject, concat, EMPTY, from, merge, Observable, ReplaySubject, Subject, timer} from 'rxjs';
import {catchError, debounce, debounceTime, scan, switchMap, take, takeUntil, tap} from 'rxjs/operators';
import * as _ from 'lodash';

export interface ProductSelectedInterface {
    checked_amount: boolean;
    amount: number;
    productId: string;
    factoryId: string;
    status?: null | number;
    code?: string;
    timecheck?: string;
    timeAdd: number;
    userNote ?: string;
    saleNote ?: string;
    gradeId: string;
    attributeId: string;
    distributorId: number;
    uomId: number;
    uomName: string;
    statusItem: number;
    dataCheckAmount: any;
}
export class ProductSelected implements ProductSelectedInterface {
    constructor (public checked_amount: boolean, public amount: number, public productId: string,
                 public timeAdd: number, public factoryId: string, gradeId: string, attributeId: string,
                 distributorId: number, uomId: number, statusItem: number, uomName: string,  dataCheckAmount: any) {}

    attributeId: string;
    distributorId: number;
    gradeId: string;
    uomId: number;
    statusItem: number;
    uomName: string;
    dataCheckAmount: any;
}

export interface OrderProductSelected {
    [code: string]: ProductSelectedInterface;
}
export interface ProductStateModel {
    products: { [key: string]: Product };
    currentProductId: number | null;
    productsInOrder: { [orderId: string]: OrderProductSelected};
    snapshots: { [orderId: string]: OrderProductSelected};
}
@State<ProductStateModel>({
    name: 'products',
    defaults: {
        products: {},
        currentProductId: null,
        productsInOrder: {},
        snapshots: {}
    }
})
export class ProductState {
    public checkAmountObservable = {};
    private amount: number ;
    public constructor(private client: ClientService, private store: Store) {}
    @Selector([OrderState])
    static getSelectedProductForOrder(state: ProductStateModel, orderState: OrderStateModel) {
        const currentOrderId = orderState.currentOrderId ? orderState.currentOrderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
        return state.productsInOrder[currentOrderId];
    }

    @Selector()
    public static getProducts(state: ProductStateModel) {
        return state.products;
    }

    static collectIdSeltectedProduct(orderId: string) {
        return createSelector(
            [ProductState],
            (state: ProductStateModel) => {
                const currentOrderId = orderId ? orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
                const clone = Object.assign({}, state.productsInOrder[currentOrderId]);
                let idsSelected: any = Object.keys(clone);
                idsSelected = idsSelected.map(x => {
                    return Number(x);
                });
                idsSelected = idsSelected.map(x => {
                    const array = [];
                    array['product_id'] = x;
                    array['amount'] = clone[x].amount;
                    return array;
                    // return Number(x);
                });
                return idsSelected;
            }
        );
    }
    @Action(GetProducts)
    async getProducts(ctx: StateContext<ProductStateModel>, action: GetProducts) {
        const state = ctx.getState();
        const products = await this.client.getProducts();
        const receivedProducts = productListToMap(products);
        ctx.patchState({
            products: receivedProducts
        });
    }

    @Action(ReceivedProducts)
    receivedProducts(ctx: StateContext<ProductStateModel>, action: ReceivedProducts) {
        const state = ctx.getState();
        const nextProducts = state.products;
        const newState = Object.assign({}, nextProducts, action.payload);
        ctx.setState({
            ...state,
            products: newState
        });
    }

    @Action(AddProductToOrder)
    addProductToOrder(ctx: StateContext<ProductStateModel>, action: AddProductToOrder) {
        const state = ctx.getState();
        const orderId = action.payload.orderId ? action.payload.orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
        const oldProducts = state.productsInOrder[orderId];
        // tslint:disable-next-line:triple-equals
        if (oldProducts && oldProducts[action.payload.code] && oldProducts[action.payload.code].amount == action.payload.amount) {
            return;
        }
        const nextProduct = {};
        const attributeProduct = {};
        attributeProduct['checked_amount'] = false;
        attributeProduct['amount'] = action.payload.amount;
        attributeProduct['productId'] = action.payload.productId;
        attributeProduct['code'] = action.payload.code;
        attributeProduct['factoryId'] = action.payload.factoryId;
        attributeProduct['gradeId'] = action.payload.gradeId;
        attributeProduct['distributorId'] = action.payload.distributorId;
        attributeProduct['attributeId'] = action.payload.attributeId;
        attributeProduct['uomId'] = action.payload.uomId;
        attributeProduct['uomName'] = action.payload.uomName;
        if (this.checkAmountObservable[attributeProduct['code']]) {
            this.checkAmountObservable[attributeProduct['code']].unsubscribe();
            this.checkAmountObservable[attributeProduct['code']] = null;
        }
        attributeProduct['timeAdd'] = new Date().getTime();
        attributeProduct['code'] = action.payload.code;
        attributeProduct['saleNote'] = action.payload.saleNote;
        attributeProduct['statusItem'] = action.payload.statusItem;
        attributeProduct['dataCheckAmount'] = action.payload.dataCheckAmount;
        nextProduct[action.payload.code] = attributeProduct;
        const dataAmount = action.payload.dataCheckAmount
        ctx.setState({
            ...state,
            productsInOrder: {
                ...state.productsInOrder,
                [orderId]: {
                    ...oldProducts,
                    ...nextProduct
                },
                [dataAmount] : {
                    ...dataAmount
                }
            }
        });
        if (! this.checkAmountObservable[action.payload.code]) {
            this.checkAmountObservable[action.payload.code] = new BehaviorSubject(action.payload.amount);
            this.checkAmountObservable[action.payload.code].asObservable().pipe(
                debounceTime(300)
            ).subscribe(val => {
                ctx.dispatch(new CheckProductAmount({code: action.payload.code,
                    amount: val, orderId: orderId, dataAmount: dataAmount})).pipe(
                        takeUntil(this.checkAmountObservable[action.payload.code])
                );
            });
        }
        this.checkAmountObservable[action.payload.code].next(action.payload.amount);
    }

    @Action(AddProductsToOrder)
    addProductsToOrder(ctx: StateContext<ProductStateModel>, action: AddProductsToOrder) {
        const state = ctx.getState();
        const orderId = action.payload[0].orderId ? action.payload[0].orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
        const nextProduct = {};
        for (const product in action.payload) {
            const attributeProduct = {};
            attributeProduct['checked_amount'] = false;
            attributeProduct['amount'] = action.payload[product].amount;
            attributeProduct['code'] = action.payload[product].code;
            attributeProduct['productId'] = action.payload[product].productId;
            attributeProduct['timeAdd'] = new Date().getTime();
            attributeProduct['userNote'] = action.payload[product].userNote;
            attributeProduct['saleNote'] = action.payload[product].saleNote;
            attributeProduct['factoryId'] = action.payload[product].factoryId;
            attributeProduct['gradeId'] = action.payload[product].gradeId;
            attributeProduct['attributeId'] = action.payload[product].attributeId;
            attributeProduct['distributorId'] = action.payload[product].distributorId;
            attributeProduct['uomId'] = action.payload[product].uomId;
            attributeProduct['uomName'] = action.payload[product].uomName;
            attributeProduct['statusItem'] = action.payload[product].statusItem;
            attributeProduct['dataCheckAmount'] = action.payload[product].dataCheckAmount;
            nextProduct[action.payload[product].code] = attributeProduct;
            if (this.checkAmountObservable[attributeProduct['code']]) {
                this.checkAmountObservable[attributeProduct['code']].unsubscribe();
                this.checkAmountObservable[attributeProduct['code']] = null;
            }
        }

        const oldProducts = state.productsInOrder[orderId];
        ctx.setState({
            ...state,
            productsInOrder: {
                ...state.productsInOrder,
                [orderId]: {
                    ...oldProducts,
                    ...nextProduct
                }
            }
        });
        // const that = this;
        // action.payload.forEach(function (product) {
        //     const orderId = product.orderId ? product.orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
        //     if (! that.checkAmountObservable[product.productId]) {
        //         that.checkAmountObservable[product.productId] = new BehaviorSubject(product.amount);
        //         that.checkAmountObservable[product.productId].asObservable().pipe(
        //             debounceTime(300)
        //         ).subscribe(val => {
        //             ctx.dispatch(new CheckProductAmount({productId: product.productId,
        //                 amount: val, orderId: orderId})).pipe(
        //                 takeUntil(that.checkAmountObservable[product.productId])
        //             );
        //         });
        //     }
        //     that.checkAmountObservable[product.productId].next(product.amount);
        // });
    }

    @Action(RemoveSelectedProductFromOrder)
    removeSelectedProductFromOrder(ctx: StateContext<ProductStateModel>, action: RemoveSelectedProductFromOrder) {
        const state = ctx.getState();
        const orderId = action.payload.orderId ? action.payload.orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
        const oldProducts = Object.assign({}, state.productsInOrder[orderId]);
        if (oldProducts && !oldProducts[action.payload.code]) {
            return;
        }
        delete oldProducts[action.payload.code];

        ctx.setState({
            ...state,
            productsInOrder: {
                ...state.productsInOrder,
                [orderId]: {
                    ...oldProducts
                }
            }
        });
    }

    @Action(RemoveAllSelectedProductFromOrder)
    removeAllSelectedProductFromOrder(ctx: StateContext<ProductStateModel>, action: RemoveAllSelectedProductFromOrder) {
        const state = ctx.getState();
        const orderId = action.payload.orderId ? action.payload.orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
        const oldOrder = Object.assign({}, state.productsInOrder);

        delete oldOrder[orderId];

        ctx.setState({
            ...state,
            productsInOrder: {
                ...oldOrder
            }
        });
    }

    @Action(ChangeAmountProductFromOrder)
    changeAmountProductFromOrder(ctx: StateContext<ProductStateModel>, action: ChangeAmountProductFromOrder) {
        const state = ctx.getState();
        const orderId = action.payload.orderId ? action.payload.orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
        const oldProducts = Object.assign({}, state.productsInOrder[orderId]);
        if (oldProducts && !oldProducts[action.payload.code]) {
            return;
        }
        oldProducts[action.payload.code].amount = action.payload.amount;
        oldProducts[action.payload.code].dataCheckAmount[9] = action.payload.amount;

        oldProducts[action.payload.code].checked_amount = false;
        oldProducts[action.payload.code].timecheck = new Date().getTime().toString();

        ctx.setState({
            ...state,
            productsInOrder: {
                ...state.productsInOrder,
                [orderId]: {
                    ...oldProducts
                }
            }
        });
        this.checkAmountObservable[action.payload.code].next(action.payload.amount);
    }

    @Action(NoteProductFromOrder)
    noteProductFromOrder(ctx: StateContext<ProductStateModel>, action: NoteProductFromOrder) {
        const state = ctx.getState();
        const orderId = action.payload.orderId ? action.payload.orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
        const oldProducts = Object.assign({}, state.productsInOrder[orderId]);
        if (oldProducts && !oldProducts[action.payload.code]) {
            return;
        }
        oldProducts[action.payload.code].userNote = action.payload.userNote;
        ctx.setState({
            ...state,
            productsInOrder: {
                ...state.productsInOrder,
                [orderId]: {
                    ...oldProducts
                }
            }
        });
    }

    @Action(ChangeStatusProductFromOrder)
    changeStatusProductFromOrder(ctx: StateContext<ProductStateModel>, action: ChangeStatusProductFromOrder) {
        const state = ctx.getState();
        const orderId = action.payload.orderId ? action.payload.orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
        const oldProducts = Object.assign({}, state.productsInOrder[orderId]);
        if (oldProducts && !oldProducts[action.payload.code]) {
            return;
        }
        oldProducts[action.payload.code].status = action.payload.status;
        oldProducts[action.payload.code].checked_amount = true;

        ctx.setState({
            ...state,
            productsInOrder: {
                ...state.productsInOrder,
                [orderId]: {
                    ...oldProducts
                }
            }
        });
    }

    @Action(ResetLoadingCheckProduct)
    resetLoadingCheckProduct(ctx: StateContext<ProductStateModel>, action: ResetLoadingCheckProduct) {
        const state = ctx.getState();
        const orderId = action.payload.orderId ? action.payload.orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
        const oldProducts = Object.assign({}, state.productsInOrder[orderId]);
        if (oldProducts && !oldProducts[action.payload.productId]) {
            return;
        }
        oldProducts[action.payload.productId].status = null;
        oldProducts[action.payload.productId].checked_amount = false;

        ctx.setState({
            ...state,
            productsInOrder: {
                ...state.productsInOrder,
                [orderId]: {
                    ...oldProducts
                }
            }
        });
    }

    @Action(CheckProductAmount)
    async checkProductAmount(ctx: StateContext<ProductStateModel>, action: CheckProductAmount) {
        let timeCheck: any;
        let data: any;
        const orderId = action.payload.orderId;
        const  dataAmount = action.payload.dataAmount;
        try {
            timeCheck = ctx.getState().productsInOrder[orderId][action.payload.code].timecheck;
            data = await this.checkAmountObservable[action.payload.code].pipe(take(1))
                .pipe(
                    switchMap((amount) => {
                        return from(this.client.checkProductAmount(action.payload.code, amount, dataAmount));
                    }
                ))
                .toPromise();
            const checkAmountAgain = await this.checkAmountObservable[action.payload.code].pipe(take(1)).toPromise();
            const timeCheckAgain = ctx.getState().productsInOrder[orderId][action.payload.code].timecheck;

            // tslint:disable-next-line:triple-equals
            if (action.payload.amount == checkAmountAgain && timeCheck == timeCheckAgain) {
                ctx.dispatch(new ChangeStatusProductFromOrder({orderId: orderId,
                    code: action.payload.code, status: 1}));
            }
            // data = await from(this.client.checkProductAmount(action.payload.productId, action.payload.amount)).pipe(
            //     switchMap(this.checkAmountObservable[action.payload.productId].pipe(take(1)))
            // );

        } catch (e) {
            ctx.dispatch(new ChangeStatusProductFromOrder({orderId: orderId,
                code: action.payload.code, status: 0}));
            const checkAmount = await this.checkAmountObservable[action.payload.code].pipe(take(1)).toPromise();
            const timeCheckAgain = ctx.getState().productsInOrder[orderId][action.payload.code].timecheck;
            // tslint:disable-next-line:triple-equals
            if (action.payload.amount == checkAmount && timeCheck == timeCheckAgain) {
                // tslint:disable-next-line:triple-equals
                if (e.server_error_id == 'products.check_amount.app_error' && e.status_code == 400) {
                    ctx.dispatch(new ChangeStatusProductFromOrder({orderId: action.payload.orderId,
                        code: action.payload.code, status: 0}));
                    // tslint:disable-next-line:triple-equals
                } else if (e.status_code == 500) {
                    ctx.dispatch(new ChangeStatusProductFromOrder({orderId: action.payload.orderId,
                        code: action.payload.code, status: 2}));
                }
            } else {
                return EMPTY;
            }
        }

    }

    @Action(SnapshotProductInOrder)
    snapshotProductInOrder(ctx: StateContext<ProductStateModel>, action: SnapshotProductInOrder) {
        const state = ctx.getState();
        const orderId = action.payload.orderId ? action.payload.orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
        // For deep clone an object, use this method
        const target = JSON.parse(JSON.stringify(state.productsInOrder[orderId]));
        ctx.setState({
            ...state,
            snapshots: {
                ...state.snapshots,
                [orderId]: {
                    ...target
                }
            }
        });
    }

    @Action(RecoverProductInOrder)
    recoverProductInOrder(ctx: StateContext<ProductStateModel>, action: RecoverProductInOrder) {
        const state = ctx.getState();
        const orderId = action.payload.orderId ? action.payload.orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
        if (orderId === constant.FAKE_ORDER_ID_WHEN_CREATING) {
            ctx.dispatch(new RemoveAllSelectedProductFromOrder(<RemoveAllProductsSelectedFromOrderPayload> {orderId: orderId}));
        } else {
            // For deep clone an object, use this method
            const target = JSON.parse(JSON.stringify(state.snapshots[orderId]));
            ctx.setState({
                ...state,
                productsInOrder: {
                    ...state.productsInOrder,
                    [orderId]: {
                        ...target
                    }
                }
            });
        }
    }

    @Action(CheckAllAmountProductsInOrder)
    checkAllAmountProductsInOrder(ctx: StateContext<ProductStateModel>, action: RecoverProductInOrder) {
        const state = ctx.getState();
        const orderId = action.payload.orderId ? action.payload.orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
        const listProductsNeedChecks = JSON.parse(JSON.stringify(state.productsInOrder[action.payload.orderId]));
        const that = this;
        // tslint:disable-next-line:forin
        for (const key in listProductsNeedChecks) {
            if (! that.checkAmountObservable[listProductsNeedChecks[key].code]) {
                that.checkAmountObservable[listProductsNeedChecks[key].code] = new BehaviorSubject(listProductsNeedChecks[key].amount);
                that.checkAmountObservable[listProductsNeedChecks[key].code].asObservable().pipe(
                    debounceTime(300),
                ).subscribe(val => {
                    ctx.dispatch(new CheckProductAmount({code: listProductsNeedChecks[key].code,
                        amount: val, orderId: orderId, dataAmount : listProductsNeedChecks[key].dataCheckAmount}));
                });
            }
            that.checkAmountObservable[listProductsNeedChecks[key].code].next(listProductsNeedChecks[key].amount);
        }
    }
    // @Action(ReceivedProductsForOrder)
    // receivedProductsForOrder(ctx: StateContext<ProductStateModel>, action: ReceivedProductsForOrderPayload) {
    //     const state = ctx.getState();
    //     if (action.products.length === 0 && state['productsInOrder'][action.orderId]) {
    //         // No new products are selected
    //         return;
    //     }
    //
    //     const productsForOrder = state['productsInOrder'][action.orderId] || [];
    //     let nextProductsForOrder = [...productsForOrder];
    //     for (const product of action.products) {
    //         product.productsSelected
    //         if (!nextProductsForOrder[product.])
    //     }
    //     console.log(state[action.orderId]);
    //     // state.productsInOrder[orderId] = [];
    //     // const orderId = action.orderId ? action.orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
    //     // const orders = {};
    //     // orders[orderId] = [];
    //     // ctx.patchState({
    //     //     productsInOrder: orders
    //     // })
    // }
}

function productListToMap(productList) {
    const products = {};
    for (let i = 0; i < productList.length; i++) {
        products[productList[i].id] = productList[i];
    }

    return products;
}
