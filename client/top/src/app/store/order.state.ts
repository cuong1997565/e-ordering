import {Action, createSelector, Selector, State, StateContext} from '@ngxs/store';
import {GetProducts} from './actions/products.action';
import {ClientError, ClientService} from './client/client.service';
import {Product} from './models/Product';
import {Order} from './models/Order';
import {AppService} from '../share/app.service';
import {
    CreateDraftOrder,
    GetOrder,
    InitOrder,
    ReceivedOrder,
    ReceivedOrders,
    RemoveCurrentOrderId,
    UpdateOrder,
    CreateOrder, UpdateDraftOrder
} from './actions/orders.action';
import {UserStateModel} from './user.state';
import {bindClientFunc} from './utils/helpers';
import * as _ from 'lodash';
import {constant} from '../config';
import {ProductStateModel} from './product.state';
import {st} from '@angular/core/src/render3';

export interface OrderStateModel {
    orders: { [key: string]: Order };
    currentOrderId: string | null;
}
@State<OrderStateModel>({
    name: 'orders',
    defaults: {
        orders: {},
        currentOrderId: null,
    }
})
export class OrderState {
    public constructor(private client: ClientService, private appService: AppService) {}
    @Selector()
    public static getCurrentOrder(state: OrderStateModel) {
        return state.currentOrderId;
    }
    @Selector()
    public static getCurrentOrderNote(state: OrderStateModel) {
        return state.orders[state.currentOrderId].creator_note;
    }
    @Selector()
    public static getCurrentOrderFactoryId(state: OrderStateModel) {
        return state.orders[state.currentOrderId].factory_id;
    }
    @Selector()
    public static getCurrentOrderStatus(state: OrderStateModel) {
        return state.orders[state.currentOrderId].status;
    }
    @Selector()
    public static getCurrentOrderAddress(state: OrderStateModel) {
        return state.orders[state.currentOrderId].deliver_address;
    }
    @Selector()
    public static getCurrentOrderType(state: OrderStateModel) {
        return state.orders[state.currentOrderId].type;
    }
    @Selector()
    public static getCurrentCreateDateOrder(state: OrderStateModel) {
        return state.orders[state.currentOrderId].created_at;
    }
    @Selector()
    public static getCurrentUpdateDateOrder(state: OrderStateModel) {
        return state.orders[state.currentOrderId].updated_at;
    }
    @Selector()
    public static getCurrentOrderCanceledDate(state: OrderStateModel) {
        return state.orders[state.currentOrderId].canceled_date;
    }
    @Selector()
    public static getCurrentOrderApprovedDate(state: OrderStateModel) {
        return state.orders[state.currentOrderId].approved_date;
    }
    @Selector()
    public static getCurrentOrderRejectedDate(state: OrderStateModel) {
        return state.orders[state.currentOrderId].rejected_date;
    }
    @Selector()
    public static getCurrentOrderConfirmDate(state: OrderStateModel) {
        return state.orders[state.currentOrderId].confirm_date;
    }
    @Selector()
    public static getCurrentOrderCompletedDate(state: OrderStateModel) {
        return state.orders[state.currentOrderId].completed_date;
    }
    @Selector()
    public static getCurrentOrderProcessingDate(state: OrderStateModel) {
        return state.orders[state.currentOrderId].processing_date;
    }

    static getDeliverDate(orderId: string) {
        return createSelector(
            [OrderState],
            (state: OrderStateModel) => {
                const currentOrderId = orderId ? orderId : constant.FAKE_ORDER_ID_WHEN_CREATING;
                const clone = Object.assign({}, state.orders[currentOrderId]);
                return clone ? clone.deliver_date : null;
            }
        );
    }

    @Action(RemoveCurrentOrderId)
    removeCurrentOrderId(ctx: StateContext<OrderStateModel>, action: RemoveCurrentOrderId) {
        const state = ctx.getState()
        ctx.patchState({
            currentOrderId: null
        });
    }

    @Action(InitOrder)
    initOrder(ctx: StateContext<OrderStateModel>) {
        const state = ctx.getState();
        ctx.patchState({
            currentOrderId: null
        });
    }

    @Action(CreateDraftOrder)
    async createDraftOrder(ctx: StateContext<OrderStateModel>, action: CreateDraftOrder) {
        let data: any;
        const state = ctx.getState();
        try {
            data = await this.client.createDraftOrder(action.payload);
        } catch (e) {
            throw e;
        }
    }

    @Action(UpdateDraftOrder)
    async updateDraftOrder(ctx: StateContext<OrderStateModel>, action: UpdateOrder) {
        let data: any;
        const state = ctx.getState();
        try {
            data = await this.client.updateDrafOrder(action.payload.id, action.payload);
        } catch (e) {
            throw e;
        }
    }

    @Action(CreateOrder)
    async createOrder(ctx: StateContext<OrderStateModel>, action: CreateOrder) {
        let data: any;
        const state = ctx.getState();
        try {
            data = await this.client.createOrder(action.payload);
        } catch (e) {
            throw e;
        }
    }

    @Action(UpdateOrder)
    async updateOrder(ctx: StateContext<OrderStateModel>, action: UpdateOrder) {
        let data: any;
        const state = ctx.getState();
        try {
            data = await this.client.updateOrder(action.payload.id, action.payload);
        } catch (e) {
            throw e;
        }
    }





    @Action(GetOrder)
    async getOrder(ctx: StateContext<OrderStateModel>, action: GetOrder) {
        let data: any;
        const getOrderFunc = bindClientFunc({
            clientFunc: this.client.getOrder,
            onSuccess: [ReceivedOrder],
            params: [action.payload.orderId],
            client: this.client
        })
        data = await getOrderFunc(ctx);
        if (data && data.error) {
            throw data.error;
        }
        const convertData = {}
        convertData[data.data.id] = data.data;
        await ctx.dispatch(new ReceivedOrders(convertData)).toPromise();
        return data;

    }
    @Action(ReceivedOrders)
    receivedOrders(ctx: StateContext<OrderStateModel>, action: ReceivedOrders) {
        const state = ctx.getState();
        const nextOrders = Object.assign({}, state.orders);
        const newState = Object.assign({}, nextOrders, action.payload);
        ctx.setState({
            ...state,
            orders: newState
        });
    }

    @Action(ReceivedOrder)
    receivedOrder(ctx: StateContext<OrderStateModel>, action: ReceivedOrder) {
        const state = ctx.getState();
        ctx.patchState({
            currentOrderId: action.payload.id,
        });
    }
}
