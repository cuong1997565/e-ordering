import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {LoginComponent} from './login/login.component';
import {ForgotPasswordComponent} from './forgot-password/forgot-password.component';
import { ForgotChangeComponent } from './forgot-change/forgot-change.component';
import { ChangeEmailComponent } from './change-email/change-email.component';
import {AuthGuard} from '../../share/guard/auth.guard';

const routes: Routes =
    [
        { path: 'login', component: LoginComponent, canActivate: [AuthGuard] },
        { path: 'forgot-password', component: ForgotPasswordComponent, canActivate: [AuthGuard]},
        { path: 'forgot-change', component: ForgotChangeComponent },
        {path: 'change-email', component: ChangeEmailComponent}


    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class AuthRoutingModule { }
