import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {LoginComponent} from './login/login.component';
import {AuthRoutingModule} from './auth-routing.module';
import {ShareModule} from '../../share/share.module';
import { ForgotPasswordComponent } from './forgot-password/forgot-password.component';
import { ForgotChangeComponent } from './forgot-change/forgot-change.component';
import { ChangeEmailComponent } from './change-email/change-email.component';

@NgModule({
    imports: [
        CommonModule,
        AuthRoutingModule,
        ShareModule,
        FormsModule,
        ReactiveFormsModule,
    ],
    declarations: [
        LoginComponent,
        ForgotPasswordComponent,
        ForgotChangeComponent,
        ChangeEmailComponent
    ],
})
export class AuthModule {}

