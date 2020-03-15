import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import * as _ from 'lodash';
import {FormData} from '../../../share/form-data';
import {constant} from '../../../config/base';

declare var $: any;

@Component({
    selector: 'app-reverse',
    templateUrl: './reverse.component.html',
    styleUrls: ['./reverse.component.css']
})
export class ReverseComponent implements OnInit {

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router) {
    }

    public ListDistributor;
    public dn_status;
    public fd;
    public ld;
    public detail;
    public arr = [];
    public E_Amount = 0;
    public E_AmountDiscount = 0;
    public pivot;
    public disabled = true;
    public disabled_distributor = false;
    private data = {
        notes: ''
    };
    public disabled_submit = false;
    public stt = 0;
    public stt2 = 0;
    public totalDiscountArr : any = [];
    public discount_for_dn : any = [];
    public Discount: any = [];
    public DiscountType: any = [];
    public permissions;

    ngOnInit() {
        this.fd = new FormData(this.data);
        this.getDiscount();

        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }

        if (this.route.snapshot.params['id']) {
            let self = this;
            this.fd.isNew = false;
            this.disabled_distributor = true;
            let url = 'dn/' + this.route.snapshot.params['id'];
            this.app.get(url).subscribe((res: any) => {
                this.ld = res.info[0];
                this.dn_status = res.info[0].status;
                this.fd.setData(res.info[0]);
                _.forEach(res.data, function (value, index) {
                    _.forEach(value.sale_order_items, function (val, i) {
                        if (res.data[index].sale_order_items[i]) {
                            _.forEach(res.info[0].items, function (v, s) {
                                if (res.data[index].sale_order_items[i].id === v.so_item_id) {
                                    res.data[index].sale_order_items[i].discount = v.discount;
                                    res.data[index].sale_order_items[i].notes = v.notes;
                                    res.data[index].sale_order_items[i].discount_type = v.discount_type;
                                    res.data[index].sale_order_items[i].curAmountDiscount = v.amount_after_discount;
                                    res.data[index].sale_order_items[i].deliver_quantity = v.deliver_quantity;
                                    res.data[index].sale_order_items[i].deliver_quantity_view = parseInt(res.data[index].sale_order_items[i].deliver_quantity) + parseInt(res.data[index].sale_order_items[i].delivered_quantity);
                                    res.data[index].sale_order_items[i].deliver_quantity_old = v.deliver_quantity;
                                    res.data[index].sale_order_items[i].remaining_quantity_old = res.data[index].sale_order_items[i].remaining_quantity;
                                    res.data[index].sale_order_items[i].remaining_quantity_view = parseInt(res.data[index].sale_order_items[i].remaining_quantity) - parseInt(res.data[index].sale_order_items[i].deliver_quantity);
                                    res.data[index].sale_order_items[i].dnRemaining = res.data[index].sale_order_items[i].remaining_quantity;
                                    res.data[index].sale_order_items[i].dnRemaining_view = parseInt(res.data[index].sale_order_items[i].remaining_quantity_view) + parseInt(res.data[index].sale_order_items[i].delivered_quantity);
                                    res.data[index].sale_order_items[i].dnRemaining_old = res.data[index].sale_order_items[i].remaining_quantity;
                                    let amount = val.unit_price * val.deliver_quantity;
                                    res.data[index].sale_order_items[i].curAmount = amount;
                                    self.E_Amount += amount;
                                    self.E_AmountDiscount += parseInt(v.amount_after_discount);
                                    res.data[index].sale_order_items[i].store_id = v.store_id;
                                    res.data[index].sale_order_items[i].dn_item_id = v.id;
                                }
                            });
                        }
                        if (val.product_attributes) {
                            if (res.data[index].sale_order_items[i]) {
                                res.data[index].sale_order_items[i].product_attr = JSON.parse(val.product_attributes);
                                if (self.arr.length === 0 && !_.find(self.arr, {so_id: res.data.id})) {
                                    self.stt = self.stt + 1;
                                    res.data[index].sale_order_items[i].stt = self.stt;
                                }

                                // _.forEach(res.data[index].sale_order_items[i].product_attr, function (v, s) {
                                //     if (v.type === self.app.constant.Attributes_Type_List) {
                                //         self.app.get('attribute-lists-of-value', {attribute_id: v.id}).subscribe((data) => {
                                //             // @ts-ignore
                                //             if (res.data[index].sale_order_items[i]) {
                                //                 // @ts-ignore
                                //                 res.data[index].sale_order_items[i].product_attr[s].listAttribute = self.app.arrToList(data.data, 'id', 'value');
                                //             }
                                //         });
                                //     }
                                // });

                                self.app.get('attribute-lists-of-value-some-field',
                                    {product_attr: res.data[index].sale_order_items[i].product_attributes})
                                    .subscribe((data: any) => {
                                        _.forEach(data.data, function (value, index_attribute) {
                                            res.data[index].sale_order_items[i].product_attr[index_attribute].listAttribute
                                                =  self.app.arrToList(value, 'id', 'value');
                                        });
                                    });
                            }
                        }


                        if (!_.find(res.info[0].items, {so_item_id: val.id})) {
                            setTimeout(() => {
                                _.remove(res.data[index].sale_order_items, function (n) {
                                    return n.id === val.id;
                                });
                            }, 100);
                        }
                    });
                    let note = {
                        id: '',
                        so_id: res.data[index].id,
                        so_name: res.data[index].so_number,
                        pivot_item: res.data[index].sale_order_items
                    };
                    self.arr.unshift(note);
                    _.forEach(self.arr, function (value_edit, index_edit) {
                        _.forEach(value_edit.pivot_item, function (val, i) {
                            if (val.discount_type) {
                                setTimeout(() => {
                                    let show_discount = '.show_discount' + val.id;
                                    let input_discount = '.input_discount' + val.id;
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


                if (res.info[0].discount_items.length > 0) {
                    console.log(res.info[0].discount_items);
                    // @ts-ignore
                    // this.totalDiscountArr.splice(0, 1);
                    for (var i = 0; i < res.info[0].discount_items.length; i++) {
                        this.totalDiscountArr.push({
                            discount_type_id: res.info[0].discount_items[i].discount_type_id,
                            discount_value: parseFloat(res.info[0].discount_items[i].discount_rate) > 0 ?
                                parseFloat(res.info[0].discount_items[i].discount_rate ) :
                                // tslint:disable-next-line:radix
                                parseInt(res.info[0].discount_items[i].discount_amount),
                            check_type_discount_value : parseFloat(res.info[0].discount_items[i].discount_rate)
                            > 0 ? this.app.constant.Type_Assign_discount_rate : this.app.constant.Type_Assign_discount_amount
                        });
                        this.changeDiscountTypeEditForm(res.info[0].discount_items[i].discount_type_id , i,
                            parseFloat(res.info[0].discount_items[i].discount_rate) > 0 ?
                                parseFloat(res.info[0].discount_items[i].discount_rate ) :
                                // tslint:disable-next-line:radix
                                parseInt(res.info[0].discount_items[i].discount_amount));
                    }
                }
            });
        }
    }

    changeDiscountTypeEditForm(e, index, amount) {
        const ADA = '#ADA' + index;
        const ADR = '#ADR' + index;
        const IADA = '.Assign_discount_amount' + index;
        const IADR = '.Assign_discount_rate' + index;
        if (e !== 'null' && e !== '') {
            // if (this.discount_for_dn.length === 0) {
            //     this.discount_for_dn.push({
            //         discount_type_id: '',
            //         discount_value: '',
            //         discount_test: 1,
            //         type_field : null
            //     });
            //
            //     this.Discount.push({});
            // }
            this.app.get('discount-types', {'id': e}).subscribe((res: any) => {
                const arr = {
                    discount_type_id: res.data[0].id,
                    discount_value: amount,
                    discount_test: 1
                };
                this.discount_for_dn.push(arr);

                this.Discount.push(res.data[0]);
                // this.Discount.push(res.data[0]);
                // this.setDataDiscount(this.field_discount_total, this.E_AmountDiscount, index);
                // if (res.data[0].is_percentage === constant.YES && res.data[0].is_custom_rate === constant.NO
                //     && res.data[0].is_stack_discount === constant.NO) {
                //     this.setDataDiscount(this.field_discount_total, this.E_AmountDiscount, index);
                // }
            });
        }
    }

    getDiscount() {
        this.app.get('discount-types').subscribe((data: any) => {
            this.DiscountType = this.app.arrToList(data.data, 'id', 'display_name');
        });
    }

    setData(index, i_child, Id, amount, field) {
        let self = this;

        function resetEAmountDiscount() {
            self.E_AmountDiscount = _.sum(_.map(self.arr, function (o) {
                return _.sumBy(o.pivot_item, function (i) {
                    return parseInt(i.curAmountDiscount);
                });
            }));
        }

        // set deliver_quantity
        let id = '.' + field + index;
        let amount_id = '.' + amount + index;
        let value = $.trim($(id).val());
        let amount_value = $.trim($(amount_id).val());

        if (amount_value) {
            _.forEach(this.arr, function (val_set, s) {
                _.forEach(val_set.pivot_item, function (val_pivot, p) {
                    if (val_pivot.id === index) {
                        self.arr[s].pivot_item[p].deliver_quantity = self.arr[s].pivot_item[p].deliver_quantity_old;
                        self.arr[s].pivot_item[p].dnRemaining = self.arr[s].pivot_item[p].dnRemaining_old;
                        amount_value = parseInt(amount_value);
                        if (amount_value >= 0 && amount_value <= self.arr[s].pivot_item[p].deliver_quantity) {
                            $(amount_id).removeAttr('required', 'required');
                            self.arr[s].pivot_item[p].deliver_quantity = self.arr[s].pivot_item[p].deliver_quantity - amount_value;
                            self.arr[s].pivot_item[p].deliver_quantity_reverse = amount_value;
                            self.arr[s].pivot_item[p].dnRemaining = parseInt(self.arr[s].pivot_item[p].dnRemaining) + amount_value;

                        } else {
                            $(amount_id).attr('required', 'required');
                        }
                        let amount = parseInt(self.arr[s].pivot_item[p].unit_price) * self.arr[s].pivot_item[p].deliver_quantity;
                        self.arr[s].pivot_item[p].curAmount = amount;
                        if (self.arr[s].pivot_item[p].discount_type == 1) {
                            let amountDiscount = parseInt(self.arr[s].pivot_item[p].unit_price) * self.arr[s].pivot_item[p].deliver_quantity * self.arr[s].pivot_item[p].discount;
                            self.arr[s].pivot_item[p].curAmountDiscount = amount - amountDiscount;
                            resetEAmountDiscount();
                        } else {
                            let amountDiscount = (parseInt(self.arr[s].pivot_item[p].unit_price) * self.arr[s].pivot_item[p].deliver_quantity) - parseInt(self.arr[s].pivot_item[p].discount);
                            self.arr[s].pivot_item[p].curAmountDiscount = amountDiscount;
                            resetEAmountDiscount();
                        }
                        self.arr[s].pivot_item[p].notes = value;
                    }

                });
            });
        }
        self.E_Amount = _.sum(_.map(this.arr, function (o) {
            return _.sumBy(o.pivot_item, function (i) {
                return parseInt(i.curAmount);
            });
        }));
    }

    save() {
        let self = this;
        let items = [];
        _.forEach(self.arr, function (val_set, s) {
            _.forEach(val_set.pivot_item, function (val_pivot, p) {
                let item = {
                    so_id: val_pivot.sale_order_id ? val_pivot.sale_order_id : '',
                    so_item_id: val_pivot.id ? val_pivot.id : '',
                    dn_item_id: val_pivot.dn_item_id ? val_pivot.dn_item_id : '',
                    deliver_quantity: val_pivot.deliver_quantity_reverse ? val_pivot.deliver_quantity_reverse : '',
                    discount_type: val_pivot.discount_type ? val_pivot.discount_type : '',
                    discount: val_pivot.discount ? val_pivot.discount : '',
                    store_id: val_pivot.store_id ? val_pivot.store_id : '',
                    notes: val_pivot.notes ? val_pivot.notes : ''
                };
                items.push(item);
            });
        });
        let url = 'dn/' + this.route.snapshot.params['id'] + '/reverse';
        this.app.post(url, {from_sale_order_items: items, notes: $.trim(this.fd.form.value.notes)}).subscribe((data: any) => {
            this.router.navigate([`delivery-note/list`]);
        });
    }

    deleteItem(so_id, so_item_id) {
        let self = this;
        _.map(this.arr, function (o) {
            return _.remove(o.pivot_item, function (i) {
                return i.id === so_item_id;
            });
        });
        if ((_.find(this.arr, {so_id: so_id}).pivot_item).length === 0) {
            _.remove(this.arr, function (n) {
                return n.so_id === so_id;
            });
        }
        _.forEach(this.arr, function (value, index) {
            _.forEach(value.pivot_item, function (val, i) {
                self.stt2 = self.stt2 + 1;
                self.arr[index].pivot_item[i].stt = self.stt2;
            });
        });
        if (this.arr.length === 0) {
            this.disabled_submit = true;
        }
        this.stt2 = 0;
    }
}
