import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CreditAccountRoutingModule } from './credit-account-routing.module';
import { ListComponent } from './list/list.component';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../share/share.module';
import { FormComponent } from './form/form.component';
import { ViewComponent } from './view/view.component';

@NgModule({
  imports: [
    CommonModule,
    CreditAccountRoutingModule,
    ReactiveFormsModule,
    ShareModule
  ],
  declarations: [ListComponent, FormComponent, ViewComponent,]
})
export class CreditAccountModule { }
