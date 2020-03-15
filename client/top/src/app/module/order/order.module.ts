import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ShareModule} from '../../share/share.module';
import {OrderRoutingModule} from './order-routing.module';
import {ListComponent} from './list/list.component';
import {AddComponent} from './add/add.component';

import {DetailComponent} from './detail/detail.component';

import { ConfirmComponent } from './confirm/confirm.component';
import { ViewComponent } from './view/view.component';
import { SaleComponent } from './sale/sale.component';
import { DeliveryNoteComponent } from './delivery-note/delivery-note.component';


@NgModule({
    imports: [
        CommonModule,
        OrderRoutingModule,
        ShareModule,
        FormsModule,
        ReactiveFormsModule,
    ],
    declarations: [
        ListComponent,
        AddComponent,
        DetailComponent,
        ConfirmComponent,
        ViewComponent,
        SaleComponent,
        DeliveryNoteComponent
    ],
})
export class OrderModule {
}
