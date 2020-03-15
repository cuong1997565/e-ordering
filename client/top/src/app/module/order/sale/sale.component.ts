import { Component, OnInit } from '@angular/core';

import {AppService} from '../../../share/app.service';
import {Store} from '@ngxs/store';
import {ActivatedRoute, Router} from '@angular/router';
import * as _ from 'lodash';



@Component({
  selector: 'app-sale',
  templateUrl: './sale.component.html',
  styleUrls: ['./sale.component.css']
})
export class SaleComponent implements OnInit {
    private data = {
        to_date: '',
        note: ''
    };
    public ld;
    public fd;
    public arr: any = [];
    public disabled;
    public priceList;
    public pivot_item;
    public listAttribute;
    public price_list_id;
    public so_status;
    public E_Amount = 0;
    public countSaleOrderDelivery = 0;
    constructor(public app: AppService, private store: Store, private route: ActivatedRoute, private router: Router) {

    }

    ngOnInit() {
        this.getSaleOrder();
        this.countSaleDelivery();

    }

    countSaleDelivery() {
      const url = 'v1/count_sale_order_about_delivery_note/' + this.route.snapshot.params['id'];
      this.app.get(url).subscribe((res) => {
            // @ts-ignore
          this.countSaleOrderDelivery = res.data;
      });
    }

    getSaleOrder() {
    const url = 'v1/sale_orders/' + this.route.snapshot.params['id'];
    this.app.get(url).subscribe((data: any) => {
        const self = this;
        // @ts-ignore
        this.ld = data.data;
        // @ts-ignore
        this.pivot_item = data.pivot_item;
        // @ts-ignore
        if (data.pivot_item) {
            // @ts-ignore
            _.forEach(data.pivot_item, function (value, index) {
                if (value.product_attributes) {
                    setTimeout(() => {
                        // @ts-ignore
                        data.pivot_item[index].product_attr = JSON.parse(value.product_attributes);

                        self.app.post('v1/attribute-lists-of-value-some-field', {product_attr: data.pivot_item[index].product_attributes})
                            .subscribe((res: any) => {
                                _.forEach(res.data, function (val, i) {
                                    data.pivot_item[index].product_attr[i].listAttribute = self.app.arrToList(val, 'id', 'value');
                                });
                            });
                    }, 300);

                        const note = {
                            id: value.id,
                            product_id: value.product.id,
                            grade_id: value.grade_id,
                            uom_id: value.uom_id,
                            sale_quantity: value.sale_quantity,
                            customer_quantity: value.customer_quantity,
                            delivered_quantity: value.delivered_quantity,
                            remaining_quantity: value.remaining_quantity,
                            status: value.status,
                            user_note: value.user_note,
                            sale_note: value.sale_note,
                            product_attributes: JSON.parse(value.product_attributes),
                        };
                        self.arr.push(note);
                }
            });
            }
        });
    }

    backPO() {
        this.router.navigate(['order/view/' + this.ld.order_id]);
    }


    redirectDeliveryNote() {
        this.router.navigate(['order/delivery-note/' + this.route.snapshot.params['id']]);
    }

}
