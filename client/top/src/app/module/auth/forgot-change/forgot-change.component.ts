import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormBuilder} from '@angular/forms';
import * as $ from 'jquery';


@Component({
    selector: 'app-forgot-change',
    templateUrl: './forgot-change.component.html',
    styleUrls: ['./forgot-change.component.css']
})
export class ForgotChangeComponent implements OnInit {

    constructor(public app: AppService, private route: ActivatedRoute, private router: Router, private _fb: FormBuilder) {
    }

    public form;
    public extra_token_time;
    public extra_token;
    public check: boolean = false;
    public time;
    public datum;
    public token_old: any = [];
    public count = 0;
    public mess: boolean = false;
    public timeout: boolean = false;

    ngOnInit() {
        // 60000 = 1 phut
        this.mess = false;
        this.token_old = this.app.getConfig('token_old') ? JSON.parse(this.app.getConfig('token_old')) : [];
        this.extra_token = this.route.snapshot.queryParams['token'];
        let dataQuery = {sort: 'name', direction: 'asc', extra_token: this.extra_token};
        this.app.get('users/checkToken', dataQuery).subscribe((res: any) => {
            if (res.length > 0) {
                this.extra_token_time = this.extra_token.substring(10, 23).toString();
                this.time = Date.now();
                this.datum = this.time - this.extra_token_time;
                const self = this;
                if (this.token_old.length > 0) {
                    this.token_old.forEach(function (value) {
                        if (self.datum > 300000 || self.extra_token_time === value) {
                            self.check = false;
                            self.timeout = true;
                        } else {
                            self.count++;
                            self.check = true;
                        }
                    });
                    if (self.timeout === true) {
                        alert('Session expired');
                        return self.router.navigate([`/customer/login`]);
                    }
                } else {
                    this.check = true;
                }

            } else {
                alert('Session expired');
                return this.router.navigate([`/`]);
            }
        });


        this.form = this._fb.group(
            {
                extra_token: '',
                password_new: '',
                password_confirmation: ''
            });
    }

    save() {
        this.form.value.extra_token = this.route.snapshot.queryParams['token'];
        this.app.post('v1/users/forgot-change', this.form.value).subscribe((data: any) => {
            $('#password_new').hide();
            $('#password_confirmation').hide();
            $('.button_black').hide();
            $('.cd-form__label').hide();
            $('.account__show-mess').hide();
            $('.help-block').hide();
            this.mess = true;
            if (this.count === 20) {
                this.token_old.shift();
            }
            this.token_old.push(this.extra_token_time);
            localStorage.setItem('token_old', JSON.stringify(this.token_old));
            // return this.router.navigate([`/`]);
        });
    }

    login() {
        this.router.navigate(['/customer/login']);
    }
}
