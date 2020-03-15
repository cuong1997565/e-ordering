import { Component, OnInit } from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

    public fd;
    public url;
    private data = {
        id: '',
        name: '',
        code: '',
        display_name: '',
        is_percentage: 0,
        is_custom_rate: 0,
        is_stack_discount: 0,
        discount_rate: ''
    };

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router
    ) { }

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_DISCOUNT_TYPE]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_DISCOUNT_TYPE]) {
            this.router.navigate(['dashboard']);
        }
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('discount-types', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
            });
        }
    }

    save() {
        this.url = 'discount-type/' + this.route.snapshot.params['id'] + '/update';
        this.fd.form.value.code = this.fd.form.value.code.trim();
        this.fd.form.value.is_percentage = this.fd.form.value.is_percentage ? 1 : 0;
        this.fd.form.value.is_custom_rate = this.fd.form.value.is_custom_rate ? 1 : 0;
        this.fd.form.value.is_stack_discount = this.fd.form.value.is_stack_discount ? 1 : 0;

        if (this.route.snapshot.params['id']) {

            this.app.post(this.url, this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Discount type has been saved');
                return this.router.navigate(['/discount-type']);
            });
        } else {
            this.app.post('discount-type', this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Discount type has been saved');
                return this.router.navigate(['/discount-type']);
            });
        }
    }

}
