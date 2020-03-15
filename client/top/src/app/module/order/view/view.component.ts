import { Component, OnInit, AfterViewInit } from '@angular/core';
import {Store} from '@ngxs/store';
import {OrderState} from '../../../store/order.state';
import * as moment from 'moment';
declare var $: any;
import {ProductState, ProductSelectedInterface} from '../../../store/product.state';
import {Product} from '../../../store/models/Product';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {UserState} from '../../../store/user.state';

@Component({
    selector: 'app-view',
    templateUrl: './view.component.html',
    styleUrls: ['./view.component.css']
})
export class ViewComponent implements OnInit, AfterViewInit {
    public deliverDate;
    public currentOrderId: string;
    public currentOrderFactory: string;
    public note: string;
    public status: number;
    public type: number;
    public createAt: string;
    public updateAt: string;
    public canceledDate: string;
    public approvedDate?: string;
    public rejectedDate?: string;
    public completedDate?: string;
    public processingDate?: string;
    public confirmDate?: string;
    public deliverAddress?: string;
    public isDisabled = false;
    public isDisabledSubmit = false;
    public dateSort = [];
    public currentProductsSelected: ProductSelectedInterface[];
    public listProductsFromStore: { [key: string]: Product };
    public check;
    public listSale = [];


    constructor(public app: AppService, private store: Store, private route: ActivatedRoute, private router: Router) {
        this.store.select(UserState.getCurrentUser).subscribe((val) => {
            this.check = val.is_admin;
        });

        this.store.select(OrderState.getCurrentOrder).subscribe((val) => {
            this.currentOrderId = val;
        });

        this.store.select(OrderState.getCurrentOrder).subscribe((val) => {
            this.currentOrderId = val;
        });

        this.store.select(ProductState.getSelectedProductForOrder).subscribe(selectedProducts => {
            const newSort = Object.values(Object.assign({}, selectedProducts));

            if (newSort.length === 0) {
                this.isDisabled = false;
                this.isDisabledSubmit = true;
            } else {
                this.isDisabled = true;
                this.isDisabledSubmit = false;
            }

            newSort.sort((a, b) => {
                const aStartsAt = a.timeAdd;
                const bStartsAt = b.timeAdd;
                return bStartsAt - aStartsAt;
            });

            this.currentProductsSelected = newSort;
        });

        this.store.select(ProductState.getProducts).subscribe(products => {
            this.listProductsFromStore = products;
        });

        this.store.select(OrderState.getCurrentOrderNote).subscribe(val => {
            this.note = val;
        });

        this.store.select(OrderState.getCurrentOrderAddress).subscribe(val => {
            this.deliverAddress = val;
        });

        this.store.select(OrderState.getCurrentOrderType).subscribe(val => {
            this.type = val;
        });

        this.store.select(OrderState.getCurrentOrderStatus).subscribe(val => {
            this.status = val;
        });

        this.store.select(OrderState.getCurrentCreateDateOrder).subscribe(val => {
            this.createAt = val;
        });

        this.store.select(OrderState.getCurrentUpdateDateOrder).subscribe(val => {
            this.updateAt = val;
        });

        this.store.select(OrderState.getCurrentOrderFactoryId).subscribe(val => {
            this.currentOrderFactory = val;
        });

        this.store.select(OrderState.getCurrentOrderCanceledDate).subscribe(val => {
            this.canceledDate = val;
            this.dateSort.push({
                name : 'Canceled date order:',
                date : val
            });
        });

        this.store.select(OrderState.getCurrentOrderApprovedDate).subscribe(val => {
            this.approvedDate = val;
            this.dateSort.push({
                name : 'Accepted date order:',
                date : val
            });
        });

        this.store.select(OrderState.getCurrentOrderCompletedDate).subscribe(val => {
            this.completedDate = val;
            this.dateSort.push({
                name : 'Closed date order:',
                date : val
            });
        });

        this.store.select(OrderState.getCurrentOrderRejectedDate).subscribe(val => {
            this.rejectedDate = val;
            this.dateSort.push({
                name : 'Rejected date order:',
                date : val
            });
        });


        this.store.select(OrderState.getCurrentOrderConfirmDate).subscribe(val => {
            this.confirmDate = val;
            this.dateSort.push({
                name : 'Sale change order date:',
                date : val
            });
        });

        this.store.select(OrderState.getCurrentOrderProcessingDate).subscribe(val => {
            this.processingDate = val;
            this.dateSort.push({
                name : 'Reviewing date order:',
                date : val
            });
        });


        this.store.select(OrderState.getDeliverDate(this.currentOrderId)).subscribe(date => {
            this.deliverDate = date ? date : moment().format('YYYY-MM-DD');
        });

        // sort date asc
        this.dateSort.sort(function(a, b) {
            a = new Date(a.date);
            b = new Date(b.date);
            return b > a ? -1 : b < a ? 1 : 0;
        });
    }

    ngOnInit() {
        this.lisDataSale();
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

    lisDataSale() {
        const url = 'v1/get_order_about_sale_order/' + this.route.snapshot.params['id'];
        this.app.get(url).subscribe((res: any) => {
            this.listSale = res.data;
        });
    }

    listOrder() {
        this.router.navigate(['order/']);
    }

    editOrder(order_id, idFactory) {
        this.router.navigate(['order/detail/' + order_id], { queryParams: {factory_id: idFactory}});
    }


    cancelOrder(currentOrderId) {
        if (confirm('Can you change the order status page?')) {
            const url  = 'v1/orders/' + currentOrderId + '/update/status';
            // @ts-ignore
            this.app.post(url).subscribe((data: any) => {
                alert('Change order status successfully');
                return this.router.navigate(['/order']);
            }, (error: any) => {
                alert('Can not update order');
                return this.router.navigate(['/order']);
            });
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

    confirmOrder (idOrder, idFactory) {
        this.router.navigate(['order/detail/' + idOrder], { queryParams: {factory_id: idFactory, isConfirm: true}});
    }


    redirectSale(idSale) {
             return this.router.navigate([`order/sale/`, idSale]);

    }

}
