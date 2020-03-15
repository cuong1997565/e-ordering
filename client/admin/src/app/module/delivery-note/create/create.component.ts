import {ChangeDetectorRef, Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import * as _ from 'lodash';
import {FormData} from '../../../share/form-data';
import {constant} from '../../../config/base';

declare var $: any;

@Component({
    selector: 'app-create',
    templateUrl: './create.component.html',
    styleUrls: ['./create.component.css']
})
export class CreateComponent implements OnInit {

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private changeDetectorRef: ChangeDetectorRef,
                private router: Router) {
    }

    public ListDistributor;
    public field_discount_total;
    public DiscountType;
    public Discount: any = [];
    public dn_status;
    public fd;
    public ld;
    public detail;
    public listSaleOrderSelect;
    public arr = [];
    public E_Amount = 0;
    public data_dn;
    public E_AmountDiscount = 0;
    public changeDiscount;
    public pivot;
    public disabled = true;
    public disabled_submit = true;
    public disabled_distributor = false;
    public discount_for_dn = [];
    private data = {
        notes: ''
    };
    public totalDiscountArr: any = [
        {
            discount_type_id: '',
            discount_value: ''
        }
    ];
    public stt = 0;
    public stt2 = 0;

    public permissions;


    ngOnInit() {
        this.fd = new FormData(this.data);
        this.getListDistributor();
        this.getDiscount();

        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }

        if (this.route.snapshot.params['id']) {
            this.disabled_submit = false;
            const self = this;
            this.fd.isNew = false;
            this.disabled_distributor = true;
            const url = 'dn/' + this.route.snapshot.params['id'];
            this.app.get(url).subscribe((res: any) => {
                this.ld = res.info[0];
                this.dn_status = res.info[0].status;
                this.fd.setData(res.info[0]);

                if (res.info[0].discount_items.length > 0) {
                    // @ts-ignore
                    this.totalDiscountArr.splice(0, 1);
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
                this.changeDistributor(res.info[0].distributor_id);
                _.forEach(res.data, function (value, index) {
                    _.forEach(value.sale_order_items, function (val, i) {
                        if (res.data[index].sale_order_items[i]) {
                            _.forEach(res.info[0].items, function (v) {

                                if (res.data[index].sale_order_items[i].id === v.so_item_id) {
                                    res.data[index].sale_order_items[i].discount = parseFloat(v.discount);
                                    res.data[index].sale_order_items[i].notes = v.notes;
                                    res.data[index].sale_order_items[i].discount_type = v.discount_type;
                                    res.data[index].sale_order_items[i].curAmountDiscount = v.amount_after_discount;
                                    res.data[index].sale_order_items[i].deliver_quantity = v.deliver_quantity;
                                    res.data[index].sale_order_items[i].remaining_quantity_view =
                                        parseFloat(v.deliver_quantity) + parseFloat(res.data[index].sale_order_items[i].remaining_quantity);
                                    const dnRemaining = res.data[index].sale_order_items[i].remaining_quantity;
                                    res.data[index].sale_order_items[i].dnRemaining = dnRemaining - val.deliver_quantity;
                                    res.data[index].sale_order_items[i].dnRemaining_confirm = dnRemaining;
                                    const amount = val.unit_price * val.deliver_quantity;
                                    res.data[index].sale_order_items[i].curAmount = amount;
                                    self.E_Amount += amount;
                                    self.E_AmountDiscount = res.info[0].amount_after_discount;
                                    res.data[index].sale_order_items[i].store_id = v.store_id;
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

                                self.app.get('attribute-lists-of-value-some-field',
                                    {product_attr: res.data[index].sale_order_items[i].product_attributes})
                                    .subscribe((data: any) => {
                                        _.forEach(data.data, function (value1, index_attribute) {
                                            res.data[index].sale_order_items[i].product_attr[index_attribute].listAttribute
                                                = self.app.arrToList(value1, 'id', 'value');
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

                    const note = {
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
    }


    getListDistributor() {
        this.app.get('distributors', {active: this.app.constant.ACTIVE_TRUE}).subscribe((res: any) => {
            this.ListDistributor = this.app.arrToList(res.data, 'id', 'name');
        });
    }

    getDiscount() {
        this.app.get('discount-types').subscribe((data: any) => {
            this.DiscountType = this.app.arrToList(data.data, 'id', 'display_name');
        });
    }

    isInt(n) {
        return n % 1 === 0;
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
                $('.selectDiscount' + index).attr('disabled', ' disabled ');
                const arr = {
                    discount_type_id: res.data[0].id,
                    discount_value: amount,
                    discount_test: 1
                };
                this.discount_for_dn.push(arr);

                this.Discount.push(res.data[0]);

                if (this.discount_for_dn.length > 0) {
                    for (var i = 0; i < this.discount_for_dn.length; i++) {
                        let field = this.isInt(this.discount_for_dn[i].discount_value) ?
                            'Assign_discount_amount' : 'Assign_discount_rate';
                        this.discount_for_dn[i].type_field = field;

                        this.setDataDiscount(this.discount_for_dn[i].type_field, this.E_AmountDiscount, i);

                    }
                }
            });
        }
    }

    changeDiscountType(e, index) {
        const ADA = '#ADA' + index;
        const ADR = '#ADR' + index;
        const IADA = '.Assign_discount_amount' + index;
        const IADR = '.Assign_discount_rate' + index;
        if (e !== 'null') {
            $('.selectDiscount' + index).attr('disabled', ' disabled ');
            this.app.get('discount-types', {'id': e}).subscribe((res: any) => {
                const sefl = this;
                const arr = {
                    discount_type_id: res.data[0].id,
                    discount_value: res.data[0].discount_rate,
                    discount_test: 1
                };
                this.discount_for_dn.push(arr);
                if (this.discount_for_dn.length > 0 ) {

                }
                this.Discount[index] =  res.data[0];


                // this.Discount = res.data[0];
                if (this.Discount[index].is_percentage === constant.NO) {
                    $(ADA).removeAttr('hidden');
                    this.totalDiscountArr[index].check_type_discount_value = this.app.constant.Type_Assign_discount_amount;

                } else {
                    this.totalDiscountArr[index].check_type_discount_value = this.app.constant.Type_Assign_discount_rate;
                    this.totalDiscountArr[index].discount_value = '';
                    $(IADA).val('');
                    $(IADR).val('');
                    $(ADA).attr('hidden', 'hidden');
                }
                if (this.Discount[index].is_percentage === constant.YES && this.Discount[index].is_custom_rate === constant.YES) {
                    $(ADR).removeAttr('hidden');
                    this.totalDiscountArr[index].check_type_discount_value = this.app.constant.Type_Assign_discount_rate;
                    this.totalDiscountArr[index].discount_value =  res.data[0].discount_rate;
                    setTimeout(function () {
                        sefl.setDataDiscount('Assign_discount_rate', sefl.E_AmountDiscount, index);
                    }, 50);
                    // this.setDataDiscount('Assign_discount_rate', this.E_AmountDiscount, index);
                } else {
                    this.totalDiscountArr[index].check_type_discount_value = this.app.constant.Type_Assign_discount_amount;
                    this.totalDiscountArr[index].discount_value = '';
                    $(IADR).val('');
                    $(IADA).val('');
                    $(ADR).attr('hidden', 'hidden');
                }
                if (this.Discount[index].is_percentage === constant.YES && this.Discount[index].is_custom_rate === constant.NO
                    && this.Discount[index].is_stack_discount === constant.NO) {
                    this.setDataDiscount(this.field_discount_total, this.E_AmountDiscount, index);
                }
            });
            if (this.discount_for_dn.length > 0 ) {
                this.discount_for_dn.forEach((function (item, i, object) {
                    if (i === index) {
                        if (item.discount_type_id !== parseFloat(e)) {
                            object.splice(i, 1);
                        }
                    }
                }));
            }
        } else {
            $(IADA).val('');
            $(IADR).val('');
            $(ADA).attr('hidden', 'hidden');
            $(ADR).attr('hidden', 'hidden');
        }
    }

    changeDistributor(e) {
        if (e > 0) {
            const queryParam = {sort: 'name', direction: 'asc', 'distributor_id': e, 'status': this.app.constant.SO_OPEN};
            this.app.get('sale_orders', queryParam).subscribe((res: any) => {
                if (res.data.length > 0) {
                    this.disabled = false;
                    this.detail = res.data[0];
                    this.listSaleOrderSelect = this.app.arrToList(res.data, 'id', 'so_number');
                } else {
                    this.disabled = true;
                    this.detail = null;
                    this.listSaleOrderSelect = null;
                }
            });
        }
    }

    Add() {
        const self = this;
        const sale_order_id = $('#sale-order').val();
        const url = 'sale_orders/' + sale_order_id;
        this.app.get(url).subscribe((data: any) => {
            this.disabled_distributor = true;
            this.disabled_submit = false;
            this.pivot = data.pivot_item;
            this.data_dn = data.data;
            _.forEach(data.pivot_item, function (value, index) {
                if (value.product_attributes) {
                    data.pivot_item[index].product_attr = JSON.parse(value.product_attributes);
                    const dnRemaining = value.remaining_quantity - value.remaining_quantity;
                    const amount = self.app.constant.Unit_brick * value.unit_price * value.remaining_quantity;
                    data.pivot_item[index].curAmount = amount;
                    data.pivot_item[index].curAmountDiscount = amount;
                    data.pivot_item[index].deliver_quantity = value.remaining_quantity;
                    data.pivot_item[index].dnRemaining = dnRemaining;
                    if (self.arr.length === 0 && !_.find(self.arr, {so_id: data.data.id})) {
                        self.stt = self.stt + 1;
                        data.pivot_item[index].stt = self.stt;
                    }
                    self.app.get('attribute-lists-of-value-some-field', {product_attr: data.pivot_item[index].product_attributes})
                        .subscribe((res: any) => {
                            _.forEach(res.data, function (val, i) {
                                data.pivot_item[index].product_attr[i].listAttribute = self.app.arrToList(val, 'id', 'value');
                            });
                        });
                }
            });

            const note = {
                id: '',
                so_id: data.data.id,
                so_name: data.data.so_number,
                pivot_item: data.pivot_item
            };
            if (!_.find(self.arr, {so_id: data.data.id})) {
                self.arr.unshift(note);
                _.forEach(this.arr, function (value, index) {
                    _.forEach(value.pivot_item, function (val, i) {
                        self.stt2 = self.stt2 + 1;
                        self.arr[index].pivot_item[i].stt = self.stt2;
                    });
                });
                this.stt2 = 0;
            } else {
                self.arr = _.unionBy([note], self.arr, 'so_id');
                _.forEach(this.arr, function (value, index) {
                    _.forEach(value.pivot_item, function (val, i) {
                        self.stt2 = self.stt2 + 1;
                        self.arr[index].pivot_item[i].stt = self.stt2;
                    });
                });

                this.stt2 = 0;
            }

            self.E_Amount = _.sum(_.map(this.arr, function (o) {
                return _.sumBy(o.pivot_item, function (i) {
                    return parseFloat(i.curAmount);
                });
            }));
            self.E_AmountDiscount = _.sum(_.map(this.arr, function (o) {
                return _.sumBy(o.pivot_item, function (i) {
                    return parseFloat(i.curAmountDiscount);
                });
            }));
        });
    }

    setData(index, i_child, Id, amount, field, field_child, change_amount, id_discount, field_discount, check_dn_quantity, index_store) {
        const self = this;
        function resetCurAmountDiscount() {
            // tslint:disable-next-line:no-shadowed-variable
            const amount_id = '.' + amount + index;
            // tslint:disable-next-line:no-shadowed-variable
            let amount_value = $.trim($(amount_id).val());
            if (amount_value) {
                _.forEach(self.arr, function (val_set, s) {
                    _.forEach(val_set.pivot_item, function (val_pivot, p) {
                        if (val_pivot.id === index) {
                            amount_value = parseFloat(amount_value);
                            // tslint:disable-next-line:no-shadowed-variable
                            const amount = parseFloat(self.arr[s].pivot_item[p].unit_price) * amount_value;
                            self.arr[s].pivot_item[p].curAmountDiscount = amount;
                        }

                    });
                });
            }
        }

        function resetEAmountDiscount() {
            self.E_AmountDiscount = _.sum(_.map(self.arr, function (o) {
                return _.sumBy(o.pivot_item, function (i) {
                    return parseFloat(i.curAmountDiscount);
                });
            }));
        }

        function isInt(n) {
            return n % 1 === 0;
        }

        // set store
        const id_child = '.' + field_child + index;
        const value_child = $(id_child).val();
        if (value_child > 0) {
            const store_required = '#store' + index_store;
            setTimeout(() => {
                $(store_required).removeAttr('required');
            }, 50);
            _.forEach(self.arr, function (val_set, s) {
                _.forEach(val_set.pivot_item, function (val_pivot, p) {
                    if (val_pivot.id === index) {
                        self.arr[s].pivot_item[p].store_id = value_child;
                    }

                });
            });
        }
        if (value_child === 'null') {
            _.forEach(self.arr, function (val_set, s) {
                _.forEach(val_set.pivot_item, function (val_pivot, p) {
                    if (val_pivot.id === index) {
                        self.arr[s].pivot_item[p].store_id = '';
                    }
                });
            });
        }
        // set discount
        const input_discount = '.input_discount' + index;
        const show_discount = '.show_discount' + index;
        const delivered_quantity = '.amount' + index;
        if (parseFloat(id_discount) === this.app.constant.Discount_Percent) {
            if (parseFloat(this.changeDiscount) === this.app.constant.Discount_Fixed && $(input_discount)[0].value !== '') {
                resetCurAmountDiscount();
                $(input_discount).val('');
                const discount = '.' + field_discount + index;
                const value_discount = $(discount).val();
                this.changeDiscount = value_discount;
                resetEAmountDiscount();
                _.forEach(self.arr, function (val_set, s) {
                    _.forEach(val_set.pivot_item, function (val_pivot, p) {
                        if (val_pivot.id === index) {
                            self.arr[s].pivot_item[p].discount_type = value_discount;
                            self.arr[s].pivot_item[p].discount = '';
                        }

                    });
                });

            } else {
                setTimeout(() => {
                    $(input_discount).removeAttr('hidden');
                    $(show_discount).removeAttr('hidden');
                }, 100);
                resetCurAmountDiscount();
                const discount = '.' + field_discount + index;
                const value_discount = $(discount).val();
                _.forEach(self.arr, function (val_set, s) {
                    _.forEach(val_set.pivot_item, function (val_pivot, p) {
                        if (val_pivot.id === index) {
                            self.arr[s].pivot_item[p].discount_type = value_discount;
                            self.arr[s].pivot_item[p].discount = '';
                        }

                    });
                });
            }

            if (this.discount_for_dn.length > 0) {
                for (var i = 0; i < this.discount_for_dn.length; i++) {
                    let field = isInt(this.discount_for_dn[i].discount_value) ?
                        'Assign_discount_amount' : 'Assign_discount_rate';
                    this.discount_for_dn[i].type_field = field;

                    this.setDataDiscount(this.discount_for_dn[i].type_field, this.E_AmountDiscount, i);

                }
            }
        }
        if (parseFloat(id_discount) === this.app.constant.Discount_Fixed) {
            if (parseFloat(this.changeDiscount) === 1 && $(input_discount)[0].value !== '') {
                resetCurAmountDiscount();
                $(input_discount).val('');
                const discount = '.' + field_discount + index;
                const value_discount = $(discount).val();
                this.changeDiscount = value_discount;
                resetEAmountDiscount();
                _.forEach(self.arr, function (val_set, s) {
                    _.forEach(val_set.pivot_item, function (val_pivot, p) {
                        if (val_pivot.id === index) {
                            self.arr[s].pivot_item[p].discount_type = value_discount;
                            self.arr[s].pivot_item[p].discount = '';
                        }

                    });
                });
            } else {
                setTimeout(() => {
                    $(input_discount).removeAttr('hidden');
                    $(show_discount).removeAttr('hidden');
                }, 100);
                resetCurAmountDiscount();
                const discount = '.' + field_discount + index;
                const value_discount = $(discount).val();
                _.forEach(self.arr, function (val_set, s) {
                    _.forEach(val_set.pivot_item, function (val_pivot, p) {
                        if (val_pivot.id === index) {
                            self.arr[s].pivot_item[p].discount_type = value_discount;
                            self.arr[s].pivot_item[p].discount = '';
                        }

                    });
                });
            }


            if (this.discount_for_dn.length > 0) {
                for (var i = 0; i < this.discount_for_dn.length; i++) {
                    let field = isInt(this.discount_for_dn[i].discount_value) ?
                        'Assign_discount_amount' : 'Assign_discount_rate';
                    this.discount_for_dn[i].type_field = field;

                    this.setDataDiscount(this.discount_for_dn[i].type_field, this.E_AmountDiscount, i);

                }
            }
        }

        if (parseFloat(id_discount) === 0) {
            setTimeout(() => {
                if ($(input_discount)[0].value !== '') {
                    const discount = '.' + field_discount + index;
                    const value_discount = $(discount).val();
                    this.changeDiscount = value_discount;
                    const input_value = $(input_discount).val();
                    if (parseFloat(value_discount) === this.app.constant.Discount_Percent && input_value) {
                        _.forEach(this.arr, function (dc_set, s) {
                            _.forEach(dc_set.pivot_item, function (dc_pivot, p) {
                                if (dc_pivot.id === index) {
                                    amount_value = parseFloat(amount_value);
                                    if (input_value > 1 || input_value < 0) {
                                        $(input_discount).attr('required', 'required');
                                    } else {
                                        $(input_discount).removeAttr('required');
                                        // tslint:disable-next-line:no-shadowed-variable
                                        const amount = parseFloat(self.arr[s].pivot_item[p].unit_price) * amount_value;
                                        self.arr[s].pivot_item[p].curAmountDiscount = amount;
                                        const percent = self.arr[s].pivot_item[p].curAmount * input_value;
                                        self.arr[s].pivot_item[p].percent = percent;
                                        self.arr[s].pivot_item[p].curAmountDiscount = self.arr[s].pivot_item[p].curAmountDiscount - percent;
                                    }
                                }
                            });
                        });

                    }
                    if (parseFloat(value_discount) === this.app.constant.Discount_Fixed && input_value) {
                        _.forEach(this.arr, function (dc_set, s) {
                            _.forEach(dc_set.pivot_item, function (dc_pivot, p) {
                                if (dc_pivot.id === index) {
                                    amount_value = parseFloat(amount_value);
                                    // tslint:disable-next-line:no-shadowed-variable
                                    const amount = parseFloat(self.arr[s].pivot_item[p].unit_price) * amount_value;
                                    if (input_value < 0 || input_value > amount) {
                                        $(input_discount).attr('required', 'required');
                                    } else {
                                        $(input_discount).removeAttr('required');
                                        self.arr[s].pivot_item[p].curAmountDiscount = amount;
                                        const fixed = input_value;
                                        self.arr[s].pivot_item[p].fixed = fixed;
                                        self.arr[s].pivot_item[p].curAmountDiscount = self.arr[s].pivot_item[p].curAmountDiscount - fixed;
                                        resetEAmountDiscount();
                                    }
                                }

                            });
                        });
                    }
                    self.E_AmountDiscount = _.sum(_.map(self.arr, function (o) {
                        return _.sumBy(o.pivot_item, function (i) {
                            return parseFloat(i.curAmountDiscount);
                        });
                    }));


                    if (this.discount_for_dn.length > 0) {
                        for (var i = 0; i < this.discount_for_dn.length; i++) {
                            let field = isInt(this.discount_for_dn[i].discount_value) ?
                                'Assign_discount_amount' : 'Assign_discount_rate';
                            this.discount_for_dn[i].type_field = field;

                            this.setDataDiscount(this.discount_for_dn[i].type_field, this.E_AmountDiscount, i);

                        }
                    }



                    _.forEach(self.arr, function (val_set, s) {
                        _.forEach(val_set.pivot_item, function (val_pivot, p) {
                            if (val_pivot.id === index) {
                                self.arr[s].pivot_item[p].discount_type = value_discount;
                                self.arr[s].pivot_item[p].discount = input_value;
                            }

                        });
                    });
                }
            }, 100);
        }
        if (id_discount === 'null' || id_discount === null) {
            resetCurAmountDiscount();

            setTimeout(() => {
                $(input_discount).val('');
                $(input_discount).attr('hidden', 'hidden');
                $(show_discount).attr('hidden', 'hidden');
                resetEAmountDiscount();

                if (this.discount_for_dn.length > 0) {
                    for (var i = 0; i < this.discount_for_dn.length; i++) {
                            let field = isInt(this.discount_for_dn[i].discount_value) ?
                                'Assign_discount_amount' : 'Assign_discount_rate';
                            this.discount_for_dn[i].type_field = field;

                            this.setDataDiscount(this.discount_for_dn[i].type_field, this.E_AmountDiscount, i);

                    }
                }

                _.forEach(self.arr, function (val_set, s) {
                    _.forEach(val_set.pivot_item, function (val_pivot, p) {
                        if (val_pivot.id === index) {
                            self.arr[s].pivot_item[p].discount_type = '';
                            self.arr[s].pivot_item[p].discount = '';
                        }

                    });
                });
            }, 100);
        }
        //
        // set deliver_quantity
        const id = '.' + field + index;
        const amount_id = '.' + amount + index;
        const value = $.trim($(id).val());
        let amount_value = $.trim($(amount_id).val());

        if (amount_value) {
            _.forEach(this.arr, function (val_set, s) {
                _.forEach(val_set.pivot_item, function (val_pivot, p) {
                    if (val_pivot.id === index) {
                        if (amount_value >= 0) {
                            amount_value = parseFloat(amount_value);
                            const dnRemaining = self.arr[s].pivot_item[p].remaining_quantity - amount_value;
                            if (amount_value > self.arr[s].pivot_item[p].remaining_quantity) {
                                $(delivered_quantity).attr('required', 'required');
                            } else {
                                $(delivered_quantity).removeAttr('required');
                            }
                            // tslint:disable-next-line:no-shadowed-variable
                            const amount = parseFloat(self.arr[s].pivot_item[p].unit_price) * amount_value;
                            self.arr[s].pivot_item[p].dnRemaining = dnRemaining;
                            self.arr[s].pivot_item[p].curAmount = amount;
                            if (parseFloat(check_dn_quantity) === 1) {
                                self.arr[s].pivot_item[p].curAmountDiscount = amount;
                                resetEAmountDiscount();
                            }
                        } else {
                            $(delivered_quantity).attr('required', 'required');
                        }
                    }

                });
            });
        }
        self.E_Amount = _.sum(_.map(this.arr, function (o) {
            return _.sumBy(o.pivot_item, function (i) {
                return parseFloat(i.curAmount);
            });
        }));
        _.forEach(self.arr, function (val_set, s) {
            _.forEach(val_set.pivot_item, function (val_pivot, p) {
                if (val_pivot.id === index) {
                    self.arr[s].pivot_item[p].deliver_quantity = amount_value;
                    self.arr[s].pivot_item[p].notes = value;
                }

            });
        });
    }

    addDiscount() {
        const note = {
            discount_type_id: '',
            discount_value: ''
        };
        this.totalDiscountArr.push(note);
    }

    resetAmountDiscount() {
        this.E_AmountDiscount = _.sum(_.map(this.arr, function (o) {
            return _.sumBy(o.pivot_item, function (i) {
                return parseFloat(i.curAmountDiscount);
            });
        }));
    }


    setDataDiscount(field, amount, index) {
        this.resetAmountDiscount();
        const totalAmountDn = _.sum(_.map(this.arr, function (o) {
            return _.sumBy(o.pivot_item, function (i) {
                return parseFloat(i.curAmountDiscount);
            });
        }));
        for ( var i = 0 ; i < this.discount_for_dn.length ; i++ ) {
                const name = 'pages_title[' + i + ']';
                const field_discount = $('input[name="' + name + '"]').attr('type_discount');
                const value =  $('.' + field_discount + i).val();
                if (this.Discount[i].is_percentage > 0) {
                    if (this.Discount[i].is_custom_rate > 0) {
                        if (field_discount === 'Assign_discount_rate') {
                            if (this.Discount[i].is_stack_discount > 0) {
                                this.discount_for_dn[i].discount_value = parseFloat(value);
                                this.discount_for_dn[i].discount_test = parseFloat(value) * this.E_AmountDiscount;
                                this.E_AmountDiscount = this.E_AmountDiscount - this.discount_for_dn[i].discount_test;
                            } else {
                                this.discount_for_dn[i].discount_value = parseFloat(value);
                                this.discount_for_dn[i].discount_test = parseFloat(value) * totalAmountDn;
                                this.E_AmountDiscount = this.E_AmountDiscount  - this.discount_for_dn[i].discount_test;

                            }
                        }
                    } else {
                        if (this.Discount[i].is_stack_discount > 0) {
                        } else {
                            this.discount_for_dn[i].discount_test = this.Discount[i].discount_rate * totalAmountDn;
                            this.discount_for_dn[i].discount_value = this.Discount[i].discount_rate;
                            this.E_AmountDiscount = this.E_AmountDiscount  - this.discount_for_dn[i].discount_test;

                        }
                    }

                } else {
                    if (field_discount === 'Assign_discount_amount') {
                        if (value > 0 && value <= this.E_AmountDiscount) {
                            $('.' + field_discount +  i).removeAttr('required');
                            this.discount_for_dn[i].discount_value = parseFloat(value);
                            this.discount_for_dn[i].discount_test = parseFloat(value);
                            this.E_AmountDiscount = this.E_AmountDiscount - this.discount_for_dn[i].discount_test;
                        }

                        if (value > this.E_AmountDiscount || value < 0) {
                            $('.' + field_discount +  i).attr('required', 'required');
                            // this.E_AmountDiscount = this.E_AmountDiscount;
                        }
                        if (value.length === 0) {
                            $('.' + field_discount +  i).removeAttr('required');
                            // this.E_AmountDiscount = this.E_AmountDiscount;
                        }
                    }
                }
        }
    }

    removeDiscount(index) {
        if (this.discount_for_dn.length > 1) {
            this.E_AmountDiscount = this.E_AmountDiscount + this.discount_for_dn[index].discount_test;
            this.discount_for_dn.splice(index, 1);
            this.totalDiscountArr.splice(index, 1);
            $('.discount-' + index).remove();
        }
    }

    save(c) {
        const self = this;
        const items = [];
        _.forEach(self.arr, function (val_set, s) {
            _.forEach(val_set.pivot_item, function (val_pivot, p) {
                const item = {
                    so_id: val_pivot.sale_order_id ? val_pivot.sale_order_id : '',
                    so_item_id: val_pivot.id ? val_pivot.id : '',
                    deliver_quantity: val_pivot.deliver_quantity ? val_pivot.deliver_quantity : '',
                    discount_type: val_pivot.discount_type ? val_pivot.discount_type : '',
                    discount: val_pivot.discount_type ? val_pivot.discount : '',
                    store_id: val_pivot.store_id ? val_pivot.store_id : '',
                    notes: val_pivot.notes ? val_pivot.notes : ''
                };
                items.push(item);
            });
        });

        const a = _.map(this.discount_for_dn, function (o) {
            return _.pick(o, ['discount_type_id', 'discount_value']);
        });

        if (c === 1) {
            this.app.post('dn', {
                items: items, notes: $.trim(this.fd.form.value.notes),
                discount_for_dn: a
            }).subscribe((data: any) => {
                this.router.navigate([`delivery-note/list`]);
            }, (error) => {
                _.forEach(error, function (e, i) {
                    const index = i.slice(6, -9);
                    const store_required = '#store' + index;
                    setTimeout(() => {
                        $(store_required).attr('required', 'required');
                    }, 50);
                });
            });
        } else if (c === 0) {
            const url = 'dn/' + this.route.snapshot.params['id'] + '/approved-and-confirm';
            this.app.post(url, {items: items,   discount_for_dn: a}).subscribe((res) => {
                this.router.navigate([`delivery-note/list`]);
            });
        } else if (c === 2) {
            this.app.post('dn/confirm', {items: items, notes: $.trim(this.fd.form.value.notes),
                discount_for_dn: a}).subscribe((data: any) => {
                this.router.navigate([`delivery-note/list`]);
            }, (error) => {
                _.forEach(error, function (e, i) {
                    const index = i.slice(6, -9);
                    const store_required = '#store' + index;
                    setTimeout(() => {
                        $(store_required).attr('required', 'required');
                    }, 50);
                });
            });
        }
    }

    update(c) {
        const self = this;
        const items = [];
        _.forEach(self.arr, function (val_set, s) {
            _.forEach(val_set.pivot_item, function (val_pivot, p) {
                const item = {
                    so_id: val_pivot.sale_order_id ? val_pivot.sale_order_id : '',
                    so_item_id: val_pivot.id ? val_pivot.id : '',
                    deliver_quantity: val_pivot.deliver_quantity ? val_pivot.deliver_quantity : '',
                    discount_type: val_pivot.discount_type ? val_pivot.discount_type : '',
                    discount: val_pivot.discount ? val_pivot.discount : '',
                    store_id: val_pivot.store_id ? val_pivot.store_id : '',
                    notes: val_pivot.notes ? val_pivot.notes : ''
                };
                items.push(item);
            });
        });

        const a = _.map(this.discount_for_dn, function (o) {
            return _.pick(o, ['discount_type_id', 'discount_value']);
        });



        if (c === 1) {
            // dn/{delivery_note_id}/update
            const url = 'dn/' + this.route.snapshot.params['id'] + '/update';
            this.app.post(url, {
                items: items,
                notes: $.trim(this.fd.form.value.notes),
                discount_for_dn: a
            }).subscribe((data: any) => {
                this.router.navigate([`delivery-note/list`]);
            }, (error) => {
                _.forEach(error, function (e, i) {
                    const index = i.slice(6, -9);
                    const store_required = '#store' + index;
                    setTimeout(() => {
                        $(store_required).attr('required', 'required');
                    }, 50);
                });
            });
        } else {
            const url = 'dn/' + this.route.snapshot.params['id'] + '/confirm';
            this.app.post(url, {
                items: items,
                notes: $.trim(this.fd.form.value.notes),
                discount_for_dn: a
            }).subscribe((data: any) => {
                this.router.navigate([`delivery-note/list`]);
            }, (error) => {
                _.forEach(error, function (e, i) {
                    const index = i.slice(6, -9);
                    const store_required = '#store' + index;
                    setTimeout(() => {
                        $(store_required).attr('required', 'required');
                    }, 50);
                });
            });
        }
    }

    deleteItem(so_id, so_item_id) {
        const self = this;
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

    Approve_deliveryWaiting() {
        const url = 'dn/' + this.route.snapshot.params['id'] + '/approve';
        if (this.dn_status === this.app.constant.deliveryWaitingApproveWhenOver) {
            // @ts-ignore
            this.app.post(url).subscribe((res: any) => {
                this.router.navigate([`delivery-note/list`]);
            });
        }
    }

    Reject_deliveryWaiting() {
        const url = 'dn/' + this.route.snapshot.params['id'] + '/reject';
        if (this.dn_status === this.app.constant.deliveryWaitingApproveWhenOver) {
            // @ts-ignore
            this.app.post(url).subscribe((res: any) => {
                this.router.navigate([`delivery-note/list`]);
            });
        }
    }

}
