import {Injectable} from '@angular/core';
import {ActivatedRouteSnapshot, Resolve} from '@angular/router';
import {Actions, ofActionCompleted, ofActionDispatched, ofActionErrored, ofActionSuccessful, Store} from '@ngxs/store';
import {ClientService} from '../../store/client/client.service';
import {GetOrder, GetOrderPayload, ReceivedOrder} from '../../store/actions/orders.action';
import {map} from 'rxjs/operators';
import * as _ from 'lodash';
import {
    AddProductsToOrder,
    AddProductToOrderPayload, CheckAllAmountProductsInOrder,
    ReceivedProducts,
    SnapshotProductInOrder,
    SnapshotProductInOrderPayload
} from '../../store/actions/products.action';
import {
    ShowLoadingDetailOrderView,
    StopLoadingDetailOrderView,
    StopShowLoadingListOrderView
} from '../../store/views/actions/order-view.action';
import {UserState} from '../../store/user.state';
import {AppService} from '../../share/app.service';

@Injectable()
export class DetailOrderResolver implements Resolve<any> {
    constructor(public app: AppService, private store: Store, private client: ClientService, private actions$: Actions) {
    }

    resolve(route: ActivatedRouteSnapshot) {
        const getOrderRequest = <GetOrderPayload> {
            orderId: route.params['id']
        };

        this.store.dispatch(new ReceivedOrder({id: route.params['id']}));
        const initOrder = new Promise(
            (resolve, reject) => {
                this.store.dispatch(new GetOrder(getOrderRequest)).subscribe(
                    (treeState) => {
                        const currentOrderId = getOrderRequest.orderId
                        const currentOrder = treeState.entities.orders.orders[currentOrderId];
                        const currentProductsInOrder = currentOrder.products;
                        const currentItemsInOrder = currentOrder.items;
                        const products = _.keyBy(currentProductsInOrder, function (item) {
                            return item.id;
                        });

                        this.store.dispatch(new ReceivedProducts(products));
                        const addProductsToOrder = currentItemsInOrder.map(item => {
                            return <AddProductToOrderPayload> {
                                amount: item.amount,
                                userNote: item.user_note,
                                productId: item.product_id,
                                orderId: currentOrderId,
                                code: item.code,
                                saleNote: item.sale_note,
                                factoryId: item.factory_id,
                                gradeId: item.grade_id,
                                attributeId: JSON.parse(item.product_attributes),
                                distributorId: item.distributor_id,
                                uomId: item.uom_id,
                                uomName: item.uom_front_end ? item.uom_front_end.name : '',
                                statusItem : item.status,
                                dataCheckAmount: JSON.parse(item.code_stock_order_product)
                            };
                        });
                        if (addProductsToOrder.length !== 0) {
                            this.store.dispatch(new AddProductsToOrder(addProductsToOrder)).subscribe((tree) => {
                                  for (let  i = 0 ; i < currentItemsInOrder.length; i++) {
                                      this.store.dispatch(new CheckAllAmountProductsInOrder({orderId: currentOrderId,
                                          dataCheckAmount : currentItemsInOrder[i].code_stock_order_product}));
                                  }
                                const snapshotPayload = <SnapshotProductInOrderPayload> {
                                    orderId: currentOrderId
                                }
                                this.store.dispatch(new SnapshotProductInOrder(snapshotPayload)).subscribe(() => {
                                    resolve(treeState);
                                }, error => {
                                    reject();
                                });
                            });
                        } else {
                            resolve(treeState);
                        }
                        return treeState;
                    });
            },
        );

        return Promise.all([
            this.client.getFeatureItems(),
            this.client.getFactories(),
            // this.client.getCategories(),
            initOrder
        ]);

    }
}

