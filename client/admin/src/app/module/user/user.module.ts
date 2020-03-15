import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UserRoutingModule } from './user-routing.module';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { ProfileComponent } from './profile/profile.component';
import { PasswordComponent } from './password/password.component';
import { ShareModule } from '../../share/share.module';
import { ReactiveFormsModule } from '@angular/forms';
import { ViewComponent } from './view/view.component';

@NgModule({
  imports: [
    CommonModule,
    UserRoutingModule,
    ShareModule,
    ReactiveFormsModule
  ],
  declarations: [ListComponent, FormComponent, ProfileComponent, PasswordComponent, ViewComponent]
})
export class UserModule { }
