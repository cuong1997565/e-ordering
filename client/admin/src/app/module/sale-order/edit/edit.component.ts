import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';
import * as _ from 'lodash';
import * as moment from 'moment';

declare var $: any;

@Component({
    selector: 'app-edit',
    templateUrl: './edit.component.html',
    styleUrls: ['./edit.component.css']
})
export class EditComponent implements OnInit {

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router) {
    }

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
    public view_disabled: boolean = true;
    public check_quantity_sale = false;

    ngOnInit() {
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_SO_ORDER]) {
            this.router.navigate(['dashboard']);
        }
        let self = this;
        this.fd = new FormData(this.data);

        this.getPriceList();

        this.checkRemainingQuantitySaleOrder();
        const url = 'sale_orders/' + this.route.snapshot.params['id'];
        this.app.get(url).subscribe((data: any) => {
            this.so_status = data.data.status;
            this.ld = data.data;
            this.fd.setData(data.data);
            this.price_list_id = data.data.price_list_id;
            this.pivot_item = data.pivot_item;
            if (data.pivot_item) {
                _.forEach(data.pivot_item, function (value, index) {
                    if (value.product_attributes) {
                        setTimeout(() => {
                            data.pivot_item[index].SoTonQD = null;
                            data.pivot_item[index].product_attr = JSON.parse(value.product_attributes);
                            let amount = value.unit_price * value.sale_quantity;
                            data.pivot_item[index].curAmount = amount;
                            self.E_Amount += amount;
                            // _.forEach(data.pivot_item[index].product_attr, function (val, i) {
                            //     if (val.type === self.app.constant.Attributes_Type_List) {
                            //         self.app.get('attribute-lists-of-value', {attribute_id: val.id}).subscribe((res) => {
                            //             // @ts-ignore
                            //             data.pivot_item[index].product_attr[i].listAttribute =
                            //             self.app.arrToList(res.data, 'id', 'value');
                            //         });
                            //     }
                            // });
                            self.app.get('attribute-lists-of-value-some-field', {product_attr: data.pivot_item[index].product_attributes})
                            .subscribe((res: any) => {
                                _.forEach(res.data, function (val, i) {
                                    data.pivot_item[index].product_attr[i].listAttribute = self.app.arrToList(val, 'id', 'value');
                                });
                            });
                        }, 300);
                    }
                    setTimeout(() => {
                        let note = {
                            id: value.id,
                            product_id: value.product.id,
                            grade_id: value.grade_id,
                            uom_id: value.uom_id,
                            sale_quantity: value.sale_quantity,
                            customer_quantity: value.customer_quantity,
                            delivered_quantity: value.delivered_quantity,
                            remaining_quantity: value.remaining_quantity,
                            unit_price_id: value.unit_price_id,
                            unit_price: value.unit_price,
                            amount: value.curAmount,
                            status: value.status,
                            user_note: value.user_note,
                            sale_note: value.sale_note,
                            product_attributes: JSON.parse(value.product_attributes),
                            code_stock_order_product: JSON.parse(value.code_stock_order_product),
                            SoTonQD: null
                        };
                        self.arr.push(note);
                    }, 500);

                    setTimeout(() => {
                        self.app.post('product/list_check_barcode', {dataCheckBarcode : value.code_stock_order_product})
                            .subscribe((res: any) => {
                                const dataSku =  JSON.parse(res.data.sku_list);
                                if (dataSku.length > 0) {
                                    self.pivot_item[index].SoTonQD = dataSku[0].SoTonQD;
                                    self.arr[index].SoTonQD = dataSku[0].SoTonQD;
                                } else {
                                    self.pivot_item[index].SoTonQD = 'Mã Sku không tồn tại trong hệ thống';
                                    self.arr[index].SoTonQD = 'Mã Sku không tồn tại trong hệ thống';
                                }
                            }, (err) => {
                                self.pivot_item[index].SoTonQD = 'Mã Sku không tồn tại trong hệ thống';
                                self.arr[index].SoTonQD = 'Mã Sku không tồn tại trong hệ thống';
                            });
                    }, 1000);
                });
            }
        });
    }

    changePriceList(e) {
        this.E_Amount = 0;
        this.price_list_id = e.target.value;
        let url = 'price-list/' + e.target.value;
        let self = this;
        this.app.get(url).subscribe((data: any) => {
            _.forEach(data.data[0].price_list_items, function (value, index) {
                _.forEach(self.pivot_item, function (val, i) {
                    if (value.product_id == val.product_id) {
                        self.pivot_item[i].unit_price = value.unit_price;
                        self.pivot_item[i].curAmount = value.unit_price * val.sale_quantity;
                        self.arr[i].unit_price = value.unit_price;
                    }
                });
            });
            self.E_Amount = _.sumBy(this.pivot_item, function (o) {
                return o.curAmount;
            });
        });
    }

    getPriceList() {
        this.app.get('price-lists').subscribe((data: any) => {
            this.priceList = this.app.arrToList(data.data, 'id', 'name');
        });
    }

    setData(index, Id, amount, field, idChild, field_child, change_amount, attr_item = 0) {
        let self = this;
        let id = '.' + field + Id;
        let id_child1 = '.' + field_child + parseInt(idChild + '' + index);

        let value_child = $.trim($(id_child1).val());
        let amount_id = '.' + amount + Id;
        let value = $.trim($(id).val());
        let amount_value = $.trim($(amount_id).val());

        if (change_amount > 0) {
            if (amount_value) {
                _.forEach(this.pivot_item, function (val, d) {
                    if (d === index) {
                        if (amount_value >= 0) {
                            amount_value = parseInt(amount_value);
                            if (amount_value > self.pivot_item[d].customer_quantity) {
                                $(amount_id).attr('required', 'required');
                            } else {
                                $(amount_id).removeAttr('required');

                            }
                            self.changeStockAmountOrder(index, amount_value);

                            self.E_Amount = _.sumBy(self.pivot_item, function (o) {
                                return o.curAmount;
                            });

                            if (val.product_attributes) {
                                setTimeout(() => {
                                    let amount = val.unit_price * amount_value;
                                    self.pivot_item[index].curAmount = amount;
                                    self.E_Amount = _.sumBy(self.pivot_item, function (o) {
                                        return o.curAmount;
                                    });
                                }, 300);
                            }
                        } else {
                            $(amount_id).attr('required', 'required');
                        }
                    }
                });
            }
        }
        this.arr[index].sale_note = value;
        this.arr[index].sale_quantity = amount_value;
        if (idChild > 0) {
            _.forEach(this.arr[index].product_attributes, function (val, d) {
                if (val.id === (idChild)) {
                    self.arr[index].product_attributes[d].attribute_label = value_child;
                    self.changeStockOrder(attr_item, index, value_child);
                }
            });
        }
    }
    /*
    * check stock  change so luong san pham
    * */
    changeStockAmountOrder(index, amount_value) {
        const self = this;

        self.arr[index].code_stock_order_product[9] = amount_value;

        setTimeout(() => {
            self.app.post('product/list_check_barcode', {dataCheckBarcode : self.arr[index].code_stock_order_product})
                .subscribe((res: any) => {
                    const dataSku =  JSON.parse(res.data.sku_list);
                    if (dataSku.length > 0) {
                        self.pivot_item[index].SoTonQD = dataSku[0].SoTonQD;
                        self.arr[index].SoTonQD = dataSku[0].SoTonQD;
                    } else {
                        self.pivot_item[index].SoTonQD = 'Mã Sku không tồn tại trong hệ thống';
                        self.arr[index].SoTonQD = 'Mã Sku không tồn tại trong hệ thống';
                    }
                }, (err) => {
                    self.pivot_item[index].SoTonQD = 'Mã Sku không tồn tại trong hệ thống';
                    self.arr[index].SoTonQD = 'Mã Sku không tồn tại trong hệ thống';
                });
        }, 1000);
    }

    /*
  * check stock  change attribute
  * */
    changeStockOrder(attr_item, index, value_child) {
        const self = this;

        if (attr_item === 0) {
            const  kichthuoc = self.arr[index].code_stock_order_product[1].split('.');
            self.arr[index].code_stock_order_product[1] = kichthuoc[0] + '.' +
                self.pivot_item[index].product_attr[attr_item].listAttribute[value_child] + '.' + kichthuoc[2];
        }

        if (attr_item === 1) {
            self.arr[index].code_stock_order_product[2] =
                self.pivot_item[index].product_attr[attr_item].listAttribute[value_child];
        }

        if (attr_item === 2) {
            self.arr[index].code_stock_order_product[3] =
                self.pivot_item[index].product_attr[attr_item].listAttribute[value_child];
        }

        if (attr_item === 3) {
            self.arr[index].code_stock_order_product[4] =
                self.pivot_item[index].product_attr[attr_item].listAttribute[value_child];
        }

        if (attr_item === 4) {
            self.arr[index].code_stock_order_product[5] =  self.pivot_item[index].product_attr[attr_item].listAttribute[value_child];
        }

        setTimeout(() => {
            self.app.post('product/list_check_barcode', {dataCheckBarcode : self.arr[index].code_stock_order_product})
                .subscribe((res: any) => {
                    const dataSku =  JSON.parse(res.data.sku_list);
                    if (dataSku.length > 0) {
                        self.pivot_item[index].SoTonQD = dataSku[0].SoTonQD;
                        self.arr[index].SoTonQD = dataSku[0].SoTonQD;
                    } else {
                        self.pivot_item[index].SoTonQD = 'Mã Sku không tồn tại trong hệ thống';
                        self.arr[index].SoTonQD = 'Mã Sku không tồn tại trong hệ thống';
                    }
                }, (err) => {
                    self.pivot_item[index].SoTonQD = 'Mã Sku không tồn tại trong hệ thống';
                    self.arr[index].SoTonQD = 'Mã Sku không tồn tại trong hệ thống';
                });
        }, 1000);
    }

    /**/
    checkRemainingQuantitySaleOrder() {
        const url = 'check_remaining_quantity_sale_order/' + this.route.snapshot.params['id'];
        this.app.get(url).subscribe((res: any) => {
             if (res.data) {
                this.check_quantity_sale = true;
             }
        });
    }

    /*
    * change status close
    * */
    closeSo() {
        const url = 'sale/' + this.route.snapshot.params['id'] + '/close';
        this.app.post(url, { }).subscribe((res: any) => {
            this.app.flashSuccess('Change status success sale order');
            return this.router.navigate([`sale-order`]);
        });
    }




    save() {
        let self = this;
        _.forEach(this.arr, function (v, k) {
            self.arr[k].product_attributes = v.product_attributes;
            self.arr[k].status = self.app.constant.SO_OPEN;
        });
        let url = 'sale_orders/confirm/' + this.route.snapshot.params['id'];
        this.app.post(url, {
            order_id: this.ld.order_id,
            so_number: this.ld.so_number,
            distributor_id: this.ld.distributor_id,
            factory_id: this.ld.factory_id,
            price_list_id: this.price_list_id,
            so_date: moment().format('YYYY-MM-DD'),
            sale_person_id: this.ld.sale_person_id,
            status: this.app.constant.SO_OPEN,
            note: $.trim(this.fd.form.value.note),
            estimated_amount: this.E_Amount,
            items: this.arr,
        }).subscribe((res: any) => {
            this.router.navigate([`/sale-order`]);
        });
    }

    update() {
        let self = this;
        _.forEach(this.arr, function (v, k) {
            self.arr[k].product_attributes = v.product_attributes;
            self.arr[k].status = self.app.constant.SO_OPEN;
        });

        let url = 'sale_orders/submit/' + this.route.snapshot.params['id'];
        this.app.post(url, {
            order_id: this.ld.order_id,
            so_number: this.ld.so_number,
            distributor_id: this.ld.distributor_id,
            factory_id: this.ld.factory_id,
            price_list_id: this.price_list_id,
            so_date: moment().format('YYYY-MM-DD'),
            sale_person_id: this.ld.sale_person_id,
            status: this.app.constant.SO_OPEN,
            note: $.trim(this.fd.form.value.note),
            estimated_amount: this.E_Amount,
            items: this.arr,
        }).subscribe((res: any) => {
            this.router.navigate([`/sale-order`]);
        });
    }
}
