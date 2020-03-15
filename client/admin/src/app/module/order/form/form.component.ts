import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';
import * as _ from 'lodash';
import * as moment from 'moment';

declare var $: any;

@Component({
    selector: 'app-form',
    templateUrl: './form.component.html',
    styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router) {
    }

    public fd;
    public status: any;
    public products = [];
    public pivot_item;
    public ld: any;
    public distributor;
    public arr = [];
    public total: any;
    public noteStatusClose = '';
    public data = {
        note: '',
    };

    public permissions;

    public disabled = false;

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_ORDER]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_ORDER]) {
            this.router.navigate(['dashboard']);
        }

        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        this.fd.isNew = false;
        let self = this;

        this.app.get('orders/detail', {'id': this.route.snapshot.params['id']}).subscribe((data: any) => {
            this.ld = data.data[0];
            this.pivot_item = data.pivot_item;
            this.CheckStatus(data.data[0].status);
            this.fd.setData(this.ld);
            _.forEach(data.pivot_item, function (value, index) {
                setTimeout(() => {
                    data.pivot_item[index].SoTonQD = null;
                    data.pivot_item[index].product_attr = JSON.parse(value.product_attributes);
                    let amount = self.app.constant.Unit_brick * value.unit_price * value.sale_quantity;
                    data.pivot_item[index].curAmount = amount;
                    // _.forEach(data.pivot_item[index].product_attr, function (val, i) {
                    //     if (val.type === self.app.constant.Attributes_Type_List) {
                    //         self.app.get('attribute-lists-of-value', {attribute_id: val.id}).subscribe((res) => {
                    //             // @ts-ignore
                    //             data.pivot_item[index].product_attr[i].listAttribute = self.app.arrToList(res.data, 'id', 'value');
                    //             console.log(data.pivot_item[index].product_attr[i].listAttribute);
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

                setTimeout(() => {
                    let note = {
                        id: value.id,
                        product_id: value.product_id,
                        order_id: value.order_id,
                        amount: value.amount,
                        code: value.code,
                        user_note: value.user_note,
                        sale_note: value.sale_note,
                        factory_id: data.data[0].factory_id,
                        grade_id: value.grade_id,
                        uom_id: value.uom_id,
                        sale_quantity: value.sale_quantity,
                        delivery_quantity: value.delivery_quantity,
                        remaining_quantity: value.remaining_quantity,
                        distributor_id: value.distributor_id,
                        product_attributes: JSON.parse(value.product_attributes),
                        sale_uom: value.sale_uom,
                        order_quantity: value.order_quantity,
                        status: value.status,
                        code_stock_order_product: JSON.parse(value.code_stock_order_product),
                        SoTonQD: null
                    };
                    if (value.order_quantity === null) {
                        value.order_quantity = 0;
                    }
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
        });
    }

    CheckStatus(status) {
        setTimeout(() => {
            if (status === this.app.constant.REJECTED_BY_SALES ||
                status === this.app.constant.CANCELLED_BY_CUSTOMER ||
                status === this.app.constant.COMPLETED || status === 9 || status === null || status === 0) {
                $('#submit_button').hide();
                $('#reject_button').hide();
                $('#reviewing_button').hide();
                $('#approved_button').hide();
                $('.sale_note-disabled').attr('disabled', 'disabled');
                $('.amount-disabled').attr('disabled', 'disabled');
                $('#admin_note').attr('disabled', 'disabled');
                $('.reject').hide();
                $('.accept').hide();
            }
            if (status === this.app.constant.DELIVERING) {
                $('#submit_button').hide();
                $('#reviewing_button').hide();
                $('#approved_button').hide();
                $('.sale_note-disabled').attr('disabled', 'disabled');
                $('.amount-disabled').attr('disabled', 'disabled');
                $('#admin_note').attr('disabled', 'disabled');
                $('.reject').hide();
                // $('.accept').hide();
            }
            if (status === this.app.constant.SALES_ACCEPTED || status === this.app.constant.WAITING_FOR_CONFIRM) {
                $('.sale_note-disabled').attr('disabled', 'disabled');
                $('#admin_note').attr('disabled', 'disabled');
                $('#submit_button').hide();
                $('#reviewing_button').hide();
                $('#approved_button').hide();
            }
            if (status === this.app.constant.REVIEWING) {
                $('#reviewing_button').hide();
            }
            if (status === this.app.constant.CLOSED) {
                $('#submit_button').hide();
                $('#reject_button').hide();
                $('#reviewing_button').hide();
                $('#approved_button').hide();
                $('.sale_note-disabled').attr('disabled', 'disabled');
                $('.amount-disabled').attr('disabled', 'disabled');
                $('#admin_note').attr('disabled', 'disabled');
                $('.reject').hide();
            }
        }, 1);
    }

    setData(Id, product_id, amount, code, user_note, field) {
        let id = '.' + field + Id;
        let amount_id = '.' + amount + Id;
        let value = $.trim($(id).val());
        let amount_value = $.trim($(amount_id).val());
        _.find(this.arr, {id: Id}).amount = amount_value;
        _.find(this.arr, {id: Id}).sale_note = value;
    }

    change(e) {

    }

    rejectItemByAdmin(idItem, status, item) {
          if (confirm('Are you sure to reject item the current po ?')) {
              if (status === this.app.constant.SUBMITED) {
                  const url = 'order-product/' + idItem + '/change/status';
                  // @ts-ignore
                  this.app.post(url).subscribe((res: any) => {
                      const className = '.reject-text' + item;
                      const accept = '.accept' + item;
                      const reject = '.reject' + item;
                      $(accept).empty();
                      $(reject).empty();
                      $(className).text('Reject').css('color', 'red');
                      // change status
                      const  arrayItem = _.find(this.arr, {id: idItem});
                      arrayItem.status = this.app.constant.STATUS_REJECT_ORDER_ITEM;
                      const statusAccept = _.find(this.arr, {status : this.app.constant.STATUS_ACCEPT_ORDER_ITEM});
                      if (statusAccept === undefined) {
                          location.reload();
                      }
                  });
              } else {
                    alert('PO items can\'t reject!');
              }
          }
    }


    ActionReject(status, order_id) {
        if (confirm('Are you sure to reject the current po ?')) {
            if (status === this.app.constant.REJECTED_BY_SALES) {
                let url = 'orders/' + order_id + '/reject';
                // @ts-ignore
                this.app.post(url, {items: this.arr, note: this.fd.form.value.note}).subscribe((res: any) => {
                    return this.router.navigate([`order`]);
                }, (error) => {
                    alert('PO can\'t reject!');
                });
            }
        }
    }

    ActionReviewing(status, order_id) {
        if (confirm('Are you sure to reviewing the current po ?')) {
            if (status === this.app.constant.REVIEWING) {
                let url = 'orders/' + order_id + '/review';
                // @ts-ignore
                this.app.post(url, {items: this.arr, note: this.fd.form.value.note}).subscribe((res: any) => {
                    return this.router.navigate([`order`]);
                });
            }
        }
    }

    ActionApproved(status, order_id) {
        if (confirm('Are you sure to approved the current po ?')) {
            if (status === this.app.constant.SALES_APPROVED) {
                const url = 'orders/' + order_id + '/approve';
                // @ts-ignore
                this.app.post(url, {items: this.arr, note: this.fd.form.value.note}).subscribe((res: any) => {
                    return this.router.navigate([`sale-order/edit`, res.data.id]);
                });
            }
        }
    }

    /*
    * close Po
    * */
    closePO() {
        if (confirm('Are you sure to close the current po ?')) {
                const url = 'check_remaining_quantity_and_status_close_about_sale_order/' + this.route.snapshot.params['id'];
                this.app.get(url).subscribe((res) => {
                   // @ts-ignore
                    if (res.data === true) {
                        $('#exampleModal').modal();
                    } else {
                        alert('Can not status order. Please close status so');
                    }
                });
        }
    }

    checkRemainingQuantityNote(event) {
        this.noteStatusClose = event.target.value;
    }

    /*
    * change status Delivering	 switch to Close
    * */
    SubmitNoteStatusClose() {
        const url = 'order/' + this.route.snapshot.params['id'] + '/close';

        if (this.noteStatusClose === '') {
            $('.note-error').html('Input field is invalid');
            return false;
        } else  {
            $('.note-error').html('');
        }

        const data  = {
            'note' : this.noteStatusClose
        };

        this.app.post(url, data).subscribe((res) => {
            $('#exampleModal').modal('hide');
            this.app.flashSuccess('Order change status success !');
            return this.router.navigate(['/order']);
        });

    }


    directSo() {
        const url = 'get_order_about_sale_order/' + this.route.snapshot.params['id'];
         this.app.get(url).subscribe((res) => {
             // @ts-ignore
             return this.router.navigate([`sale-order/edit`, res.data.id]);
         });
    }

    save() {
        let self = this;
        _.forEach(this.arr, function (v, k) {
            self.arr[k].product_attributes = JSON.stringify(v.product_attributes);
        });
        $('.check_stock').empty();
        $('.check_amount').removeAttr('required');
        let url = 'orders/' + this.ld.id + '/update';
        this.app.post(url, {
            items: this.arr,
            products: this.arr,
            factory_id: this.ld.factory_id,
            distributor_id: this.ld.distributor_id,
            creator_id: this.ld.creator_id,
            code: this.ld.code,
            note: $.trim(this.fd.form.value.note),
            status: this.ld.status,
            deliver_date: this.ld.deliver_date,
            deliver_actual: this.ld.deliver_actual,
            total: this.ld.total,
            creator_note: this.ld.creator_note,
            approved_date: this.ld.approved_date,
            canceled_date: this.ld.canceled_date,
            rejected_date: this.ld.rejected_date,
            completed_date: this.ld.completed_date,
            processing_date: this.ld.processing_date,
            confirm_date: moment().format('YYYY-MM-DD HH:mm:ss')
        }).subscribe((data: any) => {
            this.router.navigate([`order`]);
        }, (error: any) => {
            _.forEach(error, function (value, key) {
                let className = '.status_' + key.slice(9, -7);
                let amount = '.quantity_' + key.slice(9, -7);
                $(amount).attr('required', 'required');
                $(className).text(value);
            });
        });
    }
}
