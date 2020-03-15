import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';
import * as $ from 'jquery';
import * as _ from 'lodash';

@Component({
    selector: 'app-form',
    templateUrl: './form.component.html',
    styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {
    public fd;
    public ulr;
    public ld;
    public code;
    public listArea;
    public listFactory = [];
    public listProductSelect = [];
    public idCreditAccount: number;
    public arr = [];
    private data = {
        id: '',
        name: '',
        email: '',
        address: '',
        phone: '',
        area_id: null,
        code: '',
        tax_code: '',
        contact_person: '',
        active: 0,
        amount: 0,
        hold_amount: 0,
        available_amount: 0,
        credit_limit: 0
    };
    public permissions = [];
    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router) {
    }

    ngOnInit() {
        let self = this;
        this.fd = new FormData(this.data);

        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_DISTRIBUTOR]) {
            this.router.navigate(['dashboard']);
        }

        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_DISTRIBUTOR]) {
            this.router.navigate(['dashboard']);
        }

        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
            console.log(this.permissions);
        }

        let dataQuery = {sort: 'name', direction: 'asc', level: 3, active: this.app.constant.ACTIVE_TRUE};
        this.app.get('areas', dataQuery).subscribe((res: any) => {
            this.listArea = this.app.arrToList(res.data, 'id', 'name');
        });
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('distributors', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
                this.idCreditAccount = res.data[0].credit_accounts.id;
                this.fd.form.controls['amount'].patchValue(res.data[0].credit_accounts.amount);
                this.fd.form.controls['hold_amount'].patchValue(res.data[0].credit_accounts.hold_amount);
                this.fd.form.controls['available_amount'].patchValue(res.data[0].credit_accounts.available_amount);
                this.fd.form.controls['credit_limit'].patchValue(res.data[0].credit_accounts.credit_limit);
                this.ld = res.data[0];
                _.forEach(res.data[0].products, function (value) {
                    let note = {
                        id: value.pivot.id,
                        distributor_id: value.pivot.distributor_id,
                        product_id: value.pivot.product_id,
                        min_quantity: value.pivot.min_quantity,
                        max_quantity: value.pivot.max_quantity,
                        max_hold_age: value.pivot.max_hold_age,
                        code: value.code,
                        name: value.name,
                        image: value.image
                    };
                    self.arr.push(note);
                });
            });
        }
        this.factory();
    }

    save() {
        this.ulr = 'distributors/' + this.route.snapshot.params['id'];
        this.fd.form.value['email'] = $.trim(this.fd.form.value['email']);
        if (this.route.snapshot.params['id']) {
            this.fd.form.value['listProduct'] = this.arr;
            this.app.post(this.ulr, this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Distributor has been saved');
                return this.router.navigate(['/distributor']);
            });
        } else {
            this.fd.form.value['listProduct'] = this.arr;
            this.app.post('distributors', this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Distributor has been saved');
                return this.router.navigate(['/distributor']);
            });
        }
    }

    factory() {
        this.app.get('factories', {active: this.app.constant.ACTIVE_TRUE}).subscribe((res: any) => {
            this.listFactory = res.data;
        });
    }

    Product(e) {
        if (e.target.value > 0) {
            const url = 'factories/' + e.target.value + '/products/search';
            this.app.get(url).subscribe((res: any) => {
                if (res.data.length > 0) {
                    this.listProductSelect = res.data;
                } else {
                    this.listProductSelect = null;
                }
            });
        }
    }

    setData(i, product_id, min_quantity, max_quantity, max_hold_age) {
        let min_val = '.' + min_quantity + i;
        let max_val = '.' + max_quantity + i;
        let hold_val = '.' + max_hold_age + i;

        let value_min = $.trim($(min_val).val());
        let value_max = $.trim($(max_val).val());
        let value_hold = $.trim($(hold_val).val());

        _.find(this.arr, {product_id: product_id}).min_quantity = value_min;
        _.find(this.arr, {product_id: product_id}).max_quantity = value_max;
        _.find(this.arr, {product_id: product_id}).max_hold_age = value_hold;

    }

    Add() {
        let self = this;
        let factory_id = $('#factory').val();
        let product_id = $('#product').val();
        let queryParam = {sort: 'name', direction: 'asc', 'factory_id': factory_id, id: product_id};
        // tslint:disable-next-line:radix
        const found = self.arr.some(el => el.product_id === parseInt(product_id));
        if (!found) {
           this.app.get('products', queryParam).subscribe((res: any) => {
               if (self.route.snapshot.params['id']) {
                   let note = {
                       id: '',
                       distributor_id: self.route.snapshot.params['id'],
                       product_id: res.data[0].id,
                       min_quantity: '',
                       max_quantity: '',
                       max_hold_age: '',
                       code: res.data[0].code,
                       name: res.data[0].name,
                       image: res.data[0].image
                   };
                   self.arr = _.unionBy([note], self.arr, 'product_id');
               } else {
                   let note = {
                       id: '',
                       distributor_id: '',
                       product_id: res.data[0].id,
                       min_quantity: '',
                       max_quantity: '',
                       max_hold_age: '',
                       code: res.data[0].code,
                       name: res.data[0].name,
                       image: res.data[0].image
                   };
                   if (self.arr) {
                       self.arr = _.unionBy([note], self.arr, 'product_id');
                   } else {
                       self.arr.push(note);
                   }
               }
           });
       }
    }

    deleteItem(product_id) {
        let remove = _.remove(this.arr, function (n) {
            return n.product_id === product_id;
        });
        if (remove[0].id) {
            let url = 'distributors/' + remove[0].id + '/deleteDistributorProduct';
            // @ts-ignore
            this.app.post(url).subscribe((data: any) => {
            });
        }
    }

    redirectViewCreditAccount() {
        this.router.navigate(['credit-account/view/' + this.idCreditAccount]);
    }

}
