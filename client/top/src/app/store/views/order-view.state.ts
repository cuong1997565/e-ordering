import {Action, Selector, State, StateContext} from '@ngxs/store';
import {ClientService} from '../client/client.service';
import {AppService} from '../../share/app.service';
import {
    ShowLoadingDetailOrderView,
    ShowLoadingListOrderView, ShowLoadingNewOrderView,
    StopLoadingDetailOrderView, StopLoadingNewOrderView,
    StopShowLoadingListOrderView
} from './actions/order-view.action';
import {ReceivedProducts} from '../actions/products.action';
import {ProductStateModel} from '../product.state';


export interface OrderViewStateModel {
    list: {
        loading: boolean
    };
    detail: {
        loading: boolean
    };
    new: {
        loading: boolean
    }
}
@State<OrderViewStateModel>({
    name: 'view_order',
    defaults: {
        list: {
            loading: false
        },
        detail: {
            loading: false
        },
        new: {
            loading: false
        },

    }
})
export class OrderViewState {
    public constructor(private client: ClientService, private appService: AppService) {}
    @Selector()
    public static getLoadingList(state: OrderViewStateModel) {
        return state.list.loading;
    }

    @Selector()
    public static getLoadingDetail(state: OrderViewStateModel) {
        return state.detail.loading;
    }
    @Selector()
    public static getLoadingNew(state: OrderViewStateModel) {
        return state.new.loading;
    }

    @Action(ShowLoadingListOrderView)
    showLoadOrderView(ctx: StateContext<OrderViewStateModel>, action: ShowLoadingListOrderView) {
        ctx.patchState({
            list: {
                loading: true
            }
        });
    }
    @Action(StopShowLoadingListOrderView)
    stopShowLoadingListOrderView(ctx: StateContext<OrderViewStateModel>, action: StopShowLoadingListOrderView) {
        ctx.patchState({
            list: {
                loading: false
            }
        });
    }

    @Action(ShowLoadingNewOrderView)
    showLoadingNewOrderView(ctx: StateContext<OrderViewStateModel>, action: ShowLoadingNewOrderView) {
        ctx.patchState({
            new: {
                loading: true
            }
        });
    }
    @Action(StopLoadingNewOrderView)
    stopLoadingNewOrderView(ctx: StateContext<OrderViewStateModel>, action: StopLoadingNewOrderView) {
        ctx.patchState({
            new: {
                loading: false
            }
        });
    }
    @Action(ShowLoadingDetailOrderView)
    showLoadingDetailOrderView(ctx: StateContext<OrderViewStateModel>, action: ShowLoadingDetailOrderView) {
        ctx.patchState({
            detail: {
                loading: true
            }
        });
    }
    @Action(StopLoadingDetailOrderView)
    stopLoadingDetailOrderView(ctx: StateContext<OrderViewStateModel>, action: StopLoadingDetailOrderView) {
        ctx.patchState({
            detail: {
                loading: false
            }
        });
    }
}
