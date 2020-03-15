import { Component, OnInit } from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';
import * as $ from 'jquery';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

    public fd;
    public url;
    public isPriceDfault = false;
    public data = {
        id: '',
        name: '',
        code: '',
        is_default: 0,
        active: 0
    };

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router
    ) { }

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_PRICE]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_PRICE]) {
            this.router.navigate(['dashboard']);
        }
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('price-lists', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
            });
        }
        this.isPriceDefault();
    }

    isPriceDefault() {
        this.app.get('price-lists', {is_default: this.app.constant.PRICE_DEFAULT_TRUE,
            active: this.app.constant.ACTIVE_TRUE}).subscribe((res: any) => {

            if ( res.data.length > 0 ) {
                $('.checkbox-default').attr('disabled', true);
                this.isPriceDfault = false;
                } else {
                $('.checkbox-default').attr('disabled', false);
                this.isPriceDfault = true;
                }
        });
    }

    cancelPrice() {
        console.log('data response');
    }

    save() {
        this.url = 'price-list/' + this.route.snapshot.params['id'];
        this.fd.form.value.code = $.trim(this.fd.form.value.code);
        if (this.route.snapshot.params['id']) {
            this.app.post(this.url, this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Price has been saved');
                return this.router.navigate(['/price/list']);
            });
        } else {
            this.app.post('price-list/', this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Price has been saved');
                return this.router.navigate(['/price/list']);
            });

        }

    }

}
