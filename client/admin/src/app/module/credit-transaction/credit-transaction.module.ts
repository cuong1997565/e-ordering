import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CreditTransactionRoutingModule } from './credit-transaction-routing.module';
import { ReactiveFormsModule } from '@angular/forms';
import { ShareModule } from '../../share/share.module';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';


@NgModule({
    imports: [
        CommonModule,
        CreditTransactionRoutingModule,
        ReactiveFormsModule,
        ShareModule
    ],
    declarations: [ListComponent, FormComponent]
})
export class CreditTransactionModule { }
