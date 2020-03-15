import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';
import {Actions, ofActionDispatched, ofActionErrored, ofActionSuccessful, Store} from '@ngxs/store';
import {LogIn} from '../../../store/actions/users.action';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {catchError} from 'rxjs/operators';
import * as $ from 'jquery';
import {AuthGuard} from '../../../share/guard/auth.guard';
import {constant} from '../../../config';

@Component({
    selector: 'app-login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
    loginForm: FormGroup;
    isSubmitted = false;
    serverError = '';
    sessionTimeout = '';
    @ViewChild('signin_loading') loadingSignin: ElementRef;

    constructor(public app: AppService, private  route: ActivatedRoute, private router: Router,
                private store: Store, private formBuilder: FormBuilder, private actions$: Actions,
                private el: ElementRef, private auth: AuthGuard) {

    }

    ngOnInit() {
        this.route.queryParams.subscribe(params => {
            if (params['active']) {
                let id = params['active'].slice(10, -10);
                this.app.post('v1/customers/changeActiveClient', {active: 1, id: id}).subscribe((res: any) => {
                });
            }
            console.log(params);
            if (!params['extra'] && this.app.getConfig(constant.WAS_LOGGED_IN) === 'true') {
                this.router.navigate(['customer/login'], {
                    queryParams: {
                        extra: constant.SESSION_EXPIRED
                    },
                    queryParamsHandling: 'merge',
                    skipLocationChange: false,
                    replaceUrl: true
                    // preserve the existing query params in the route
                });
            } else if (params['extra'] && this.app.getConfig(constant.WAS_LOGGED_IN) === 'true') {
                this.serverError = 'Session Expired';
            }
        });

        if (this.route.snapshot.queryParams['extra']) {
            this.serverError = 'Session Expired';
        }
        this.loginForm = this.formBuilder.group({
            email: ['', Validators.required],
            password: ['', Validators.required]
        });
    }

    get formControls() {
        return this.loginForm.controls;
    }

    public submit(data = '') {
        if (this.loginForm.value['email'] === '' || this.loginForm.value['email'] === null) {
            $('#email').attr('required', 'required');
        } else {
            $('#email').removeAttr('required');
        }

        if (this.loginForm.value['password'] === '' || this.loginForm.value['password'] === null) {
            $('#pass').attr('required', 'required');
        } else {
            $('#pass').removeAttr('required');
        }

        this.isSubmitted = true;
        this.serverError = '';
        if (this.loginForm.invalid) {
            return;
        }
        this.loadingSignin.nativeElement.style.visibility = 'visible';

        this.store.dispatch(new LogIn({email: this.loginForm.value.email, password: this.loginForm.value.password}))
            .subscribe(success => {
                // this.auth.resetSourceAuth();
                this.finishSignin();
            }, error => {
                this.loadingSignin.nativeElement.style.visibility = 'hidden';
                if (error.server_error_id === 'store.customer.get_by_email_or_username.first.app_error' ||
                    error.server_error_id === 'model.customer.do_login.wrong_password') {
                    this.serverError = 'Invalid email or password';
                    $('#pass').attr('required', 'required');
                    $('#email').attr('required', 'required');
                }
                if (error.server_error_id === 'model.customer.do_login.not_active') {
                    this.serverError = error.message;
                }
                if (error.server_error_id === 'api.invalid_url_param.validate_error') {
                    this.serverError = error.message;
                    $('#email').attr('required', 'required');
                }
                this.isSubmitted = false;
                // this.loginForm.reset();
            });
    }

    public resetForm(event: any) {
        this.serverError = '';
    }

    finishSignin = () => {
        this.app.setConfig(constant.WAS_LOGGED_IN, true);
        this.route.queryParams.subscribe(params => {
            let redirectTo = params['redirect_to'];
            if (redirectTo) {
                redirectTo = decodeURIComponent(redirectTo);
                this.loadingSignin.nativeElement.style.visibility = 'hidden';
                this.router.navigate([redirectTo]);
            } else {
                this.loadingSignin.nativeElement.style.visibility = 'hidden';
                this.router.navigate(['/order']);
            }
        });
    };

    forgot() {
        this.router.navigate(['customer/forgot-password']);
    }

}
