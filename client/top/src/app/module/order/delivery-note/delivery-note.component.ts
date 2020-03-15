import { Component, OnInit } from '@angular/core';
import {AppService} from '../../../share/app.service';
import {Store} from '@ngxs/store';
import {ActivatedRoute, Router} from '@angular/router';
import * as _ from 'lodash';
declare var $: any;

@Component({
  selector: 'app-delivery-note',
  templateUrl: './delivery-note.component.html',
  styleUrls: ['./delivery-note.component.css']
})
export class DeliveryNoteComponent implements OnInit {
    public fd;
    public ld;
    public detail;
    public arr = [];
    private data = {
        notes: ''
    };
    public stt = 0;
    public stt2 = 0;

  constructor(public app: AppService, private store: Store, private route: ActivatedRoute, private router: Router) { }

  ngOnInit() {
      this.getClientDeliveryNote();
  }

    getClientDeliveryNote() {
        const url = 'v1/dn/' + this.route.snapshot.params['id'];
        this.app.get(url).subscribe((res: any) => {
            const self = this;
            // @ts-ignore
            _.forEach(res.data, function (value, index) {
                _.forEach(value.sale_order_items, function (val, i) {
                    // @ts-ignore
                    if (res.data[index].sale_order_items[i]) {
                        if (val.product_attributes) {
                            // @ts-ignore
                            if (res.data[index].sale_order_items[i]) {
                                // @ts-ignore
                                res.data[index].sale_order_items[i].product_attr = JSON.parse(val.product_attributes);
                                // @ts-ignore
                                if (self.arr.length === 0 && !_.find(self.arr, {so_id: res.data.id})) {
                                    self.stt = self.stt + 1;
                                    // @ts-ignore
                                    res.data[index].sale_order_items[i].stt = self.stt;
                                }

                                self.app.post('v1/attribute-lists-of-value-some-field',
                                    {product_attr: res.data[index].sale_order_items[i].product_attributes})
                                    .subscribe((data: any) => {
                                        // tslint:disable-next-line:no-shadowed-variable
                                            _.forEach(data.data, function (value, index_attribute) {
                                                res.data[index].sale_order_items[i].product_attr[index_attribute].listAttribute
                                                    =  self.app.arrToList(value, 'id', 'value');
                                            });

                                     });

                            }
                        }
                    }
                });

                const note = {
                    id: '',
                    // @ts-ignore
                    so_id: res.data[index].id,
                    // @ts-ignore
                    so_name: res.data[index].so_number,
                    // @ts-ignore
                    pivot_item: res.data[index].sale_order_items
                };
                self.arr.unshift(note);
                _.forEach(self.arr, function (value_edit, index_edit) {
                    _.forEach(value_edit.pivot_item, function (val, i) {
                        if (val.discount_type) {
                            setTimeout(() => {
                                const show_discount = '.show_discount' + val.id;
                                const input_discount = '.input_discount' + val.id;
                                $(input_discount).removeAttr('hidden');
                                $(show_discount).removeAttr('hidden');
                            }, 500);
                        }
                        self.stt2 = self.stt2 + 1;
                        self.arr[index_edit].pivot_item[i].stt = self.stt2;
                    });
                });
                self.stt2 = 0;
            });
        });

    }

    backSO() {
        this.router.navigate(['order/sale/' + this.route.snapshot.params['id']]);
    }


}
