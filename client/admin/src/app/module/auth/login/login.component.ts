import {Component, OnInit} from '@angular/core';
import {FormBuilder} from '@angular/forms';
import {Router} from '@angular/router';
import {AppService} from '../../../share/app.service';

@Component({
    selector: 'app-auth-login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

    constructor
    (
        public app: AppService,
        private router: Router,
        private _fb: FormBuilder
    ) {
    }

    public form;

    ngOnInit() {
        this.form = this._fb.group(
            {
                username: 'admin',
                password: '12345678'
            });
        if (localStorage.AUTH_TOKEN) {
            return this.router.navigate([`/dashboard`]);
        }

    }

    login() {
        this.app.post('users/login', this.form.value).subscribe((data: any) => {
            this.app.setConfig('AUTH_TOKEN', data.token);
            this.app.curUser = JSON.stringify(data.profile);
            this.app.flashSuccess('You are now logged in');
            this.router.navigate(['dashboard']);
        });
    }
}
