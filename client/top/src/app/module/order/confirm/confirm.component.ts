import {AfterViewInit, Component, Input, OnInit} from '@angular/core';
declare var $: any;
import {AppService} from '../../../share/app.service';
import {CreateDraftOrderRequest, Order, UpdateOrderRequest} from '../../../store/models/Order';
import {ActivatedRoute, Router} from '@angular/router';
import {Select, Store} from '@ngxs/store';
import {OrderState} from '../../../store/order.state';
import {UserState} from '../../../store/user.state';
import {
    RemoveAllSelectedProductFromOrder,
} from '../../../store/actions/products.action';
import {Product} from '../../../store/models/Product';
import { ProductSelectedInterface, ProductState} from '../../../store/product.state';
import {CreateDraftOrder, UpdateOrder, CreateOrder, UpdateDraftOrder} from '../../../store/actions/orders.action';
import * as moment from 'moment';

@Component({
    selector: 'app-confirm',
    templateUrl: './confirm.component.html',
    styleUrls: ['./confirm.component.css']
})
export class ConfirmComponent implements OnInit, AfterViewInit {
    @Input('currentProducts') iscurrentProducts: any = [{}];
    @Input('listProductsStore') isListProductsStore: any = [{}];
    @Input('isNote') note: any = '';
    @Input('isFactoryId') factoryId: any = '';
    @Input('isType') type: any = null;
    @Input('isAddress') address: any = '';

    public isDetail: any = true;
    public listProductsFromStore: { [key: string]: Product };
    public currentProductsSelected: ProductSelectedInterface[];
    public currentOrderId: string;
    public currentOrderStatus: number;
    public currentUserId: number;
    public isConfirm: boolean;
    public currentUserDistributorId: number;
    public currentUserDitributorCode: string;
    public isDate = false;
    public isDisabledSave = false;
    public data = {
        factory_id : '',
    };
    public currentProductSelectedIds: number[];
    public deliverDate;

    constructor(public app: AppService, private route: ActivatedRoute,
                private router: Router, private store: Store
    ) {
        this.store.select(OrderState.getCurrentOrder).subscribe((val) => {
            this.currentOrderId = val;
        });

        this.store.select(OrderState.getCurrentOrder).subscribe((val) => {
            this.currentOrderId = val;
        });

        this.store.select(ProductState.getProducts).subscribe(products => {
            this.listProductsFromStore = products;
        });

        this.store.select(UserState.getCurrentUserId).subscribe(id => {
            this.currentUserId = id;
        });

        this.store.select(ProductState.collectIdSeltectedProduct(this.currentOrderId)).subscribe(ids => {
            this.currentProductSelectedIds = ids;
        });


        this.store.select(OrderState.getCurrentOrderFactoryId).subscribe((val) => {
            // this.currentOrderFactoryId = val;
            this.data.factory_id = val;
        });

        this.store.select(OrderState.getDeliverDate(this.currentOrderId)).subscribe(date => {
            this.deliverDate = date ? date : moment().format('YYYY-MM-DD');
        });

        this.store.select(OrderState.getCurrentOrderStatus).subscribe((val) => {
            this.currentOrderStatus = val;
        });

        this.store.select(UserState.getCurrentDitributorUser).subscribe(id => {
            this.currentUserDistributorId = id;
        });

        this.store.select(UserState.getCurrentDitributorCodeUser).subscribe(code => {
            this.currentUserDitributorCode = code;
        });

    }

    ngOnInit() {
        this.route.queryParams.subscribe((queryParams: any) => {
            // get param brand_id url
            this.data.factory_id = queryParams.factory_id ? queryParams.factory_id : '';
            this.isConfirm = queryParams.isConfirm ? queryParams.isConfirm : true;
        });
    }

    ngAfterViewInit() {
        $('.datepicker').datepicker('destroy');
        $('#ui-datepicker-div').remove();
        const self = this;
        $('.from_date').datepicker({
            prevText: '<<',
            nextText: '>>',
            dateFormat: 'yy-mm-dd',
        });
        $('.from_date').datepicker('setDate', self.deliverDate);
        $('.from_date').datepicker('option', 'minDate', self.deliverDate);
    }

    backDetail() {
        // console.log(this.isConfirm);
        // if (this.isConfirm) {
        //     console.log('khong');
        //     this.router.navigate(['order/view/' + currentOrderId]);
        //
        // } else  {
        //     console.log('co');
        //     this.isDetail = false;
        //
        // }
        this.isDetail = false;
        // if (idCurrentOrder !== null) {
        //     this.router.navigate(['order/detail/' + idCurrentOrder], { queryParams: {factory_id: idFactory}});
        // } else  {
        //     this.router.navigate(['order/add/'], { queryParams: {factory_id: idFactory}});
        // }
    }

    backView(idCurrentOrder) {
        this.router.navigate(['order/view/' + idCurrentOrder]);
    }

    saveDraftOrder() {
        const today = new Date().toJSON().slice(0, 10).replace(/-/g, '-');
        if (Date.parse(today) > Date.parse($('#from_date1').val())) {
            this.isDate = true;
        } else {
            if (this.note === undefined) {
                this.note = '';
            }
            if (this.address === undefined) {
                this.address = '';
            }
            const currentday = new Date();
            const dd = String(currentday.getDate()).padStart(2, '0');
            const mm = String(currentday.getMonth() + 1).padStart(2, '0');
            const yyyy = currentday.getFullYear();
            const current = yyyy + mm + dd;

            const order = <CreateDraftOrderRequest>{
                factory_id: this.factoryId,
                code: Math.random().toString(),
                creator_id: this.currentUserId,
                deliver_date: $('#from_date1').val(),
                creator_note: this.note,
                type: this.type,
                deliver_address: this.address,
                distributor_id: this.currentUserDistributorId,
                phone: 'PO-PRIME-' + this.currentUserDitributorCode + '-' + current + '-001',
                products: [],
                items: []
            };
            // tslint:disable-next-line:forin
            for (const orderIndex in this.iscurrentProducts) {
                const newOrderProduct = {};
                newOrderProduct['product_id'] = this.iscurrentProducts[orderIndex].productId;
                newOrderProduct['amount'] = this.iscurrentProducts[orderIndex].amount;
                newOrderProduct['code'] = this.iscurrentProducts[orderIndex].code;
                newOrderProduct['user_note'] = this.iscurrentProducts[orderIndex].userNote;
                newOrderProduct['factory_id'] = this.iscurrentProducts[orderIndex].factoryId;
                newOrderProduct['grade_id'] = this.iscurrentProducts[orderIndex].gradeId;
                newOrderProduct['distributor_id'] = this.iscurrentProducts[orderIndex].distributorId;
                newOrderProduct['attributes'] = this.iscurrentProducts[orderIndex].attributeId;
                newOrderProduct['uom_id'] = this.iscurrentProducts[orderIndex].uomId;
                newOrderProduct['dataCheckAmount'] = this.iscurrentProducts[orderIndex].dataCheckAmount;
                order.products.push(newOrderProduct);
                order.items.push(newOrderProduct);
            }

            if (this.currentOrderId) {
                this.isDisabledSave = true;
                order['id'] = this.currentOrderId;
                const updateOrder = <UpdateOrderRequest>Object.assign({}, order);
                this.store.dispatch(new UpdateOrder(updateOrder)).subscribe(success => {
                    this.router.navigate(['order']);
                    setTimeout(() => {
                        this.store.dispatch(new RemoveAllSelectedProductFromOrder({orderId: this.currentOrderId}));
                    }, 100);
                }, (error) => {
                    this.isDisabledSave = false;

                    if (!(this.app.constant.WAITING_FOR_CONFIRM_ORDER === this.currentOrderStatus
                        || this.app.constant.CUSTOMER_SUBMITED_ORDER === this.currentOrderStatus
                        || this.app.constant.WAITING_FOR_DRAF_ORDER === this.currentOrderStatus)) {
                        alert('Can not update order');
                        this.router.navigate(['order/view/' + this.currentOrderId]);
                    }

                    const errorCreateOrder = error.toString().split('.');
                    const convertArray = JSON.parse(errorCreateOrder[1]);
                    if (convertArray.length > 0) {
                        // @ts-ignore
                        for (const i = 0; i < convertArray.length;  i++) {
                            $('#error-amount-' + convertArray[i].key).html(convertArray[i].message);
                        }
                    }

                });
            } else {
                this.isDisabledSave = true;
                this.store.dispatch(new CreateOrder(order)).subscribe(success => {
                    this.router.navigate(['order']);
                    // setTimeout(() => {
                    //     this.store.dispatch(new RemoveAllSelectedProductFromOrder({orderId: this.currentOrderId}));
                    // }, 100);
                }, error => {
                    this.isDisabledSave = false;
                    console.log(error);
                    const errorCreateOrder = error.toString().split('.');
                    const convertArray = JSON.parse(errorCreateOrder[1]);
                    if (convertArray.length > 0) {
                        // @ts-ignore
                        for (const i = 0; i < convertArray.length;  i++) {
                            $('#error-amount-' + convertArray[i].key).html(convertArray[i].message);
                        }
                    }
                });
            }
        }
    }

    slideString(name) {
        let data = '';
        if (name.length > 10) {
            data =  name.slice(0, 10) + '...';
        } else {
            data = name;
        }
        return data;

    }

}
