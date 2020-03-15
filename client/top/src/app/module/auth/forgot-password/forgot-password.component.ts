import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormBuilder} from '@angular/forms';
import * as $ from 'jquery';

@Component({
    selector: 'app-forgot-password',
    templateUrl: './forgot-password.component.html',
    styleUrls: ['./forgot-password.component.css']
})
export class ForgotPasswordComponent implements OnInit {


    constructor(public app: AppService, private route: ActivatedRoute, private router: Router, private _fb: FormBuilder) {
    }

    public form;
    public time_token = Date.now();


    ngOnInit() {
        $('.login__content-padding').hide();
        this.form = this._fb.group(
            {
                time_token: '',
                email: ''
            });
        if (this.app.curMember && this.app.curMember.customer_code &&
            this.app.curMember.active === this.app.constant.INIT_PASS_FLAG_TRUE) {
            return this.router.navigate([`/`]);
        }
    }

    save() {
        this.form.value.time_token = this.time_token;
        this.app.post('v1/users/forgot-password', this.form.value).subscribe((data: any) => {
            $('#email').hide();
            $('.button_black').hide();
            $('.cd-form__label').hide();
            $('.account__show-mess').hide();
            $('.help-block').hide();
            $('.login__content-padding').show();
            // return this.router.navigate([`/customer/login`], {queryParams: {'email': this.form.value.email}});
        }, (error) => {
            $('#email').attr('required', 'required');
        });
    }

    login() {
        this.router.navigate(['/customer/login']);
    }
}
