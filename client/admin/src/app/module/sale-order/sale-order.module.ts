import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ShareModule} from '../../share/share.module';
import {SaleOrderRoutingModule} from './sale-order-routing.module';
import {ListComponent} from './list/list.component';
import {CreateComponent} from './create/create.component';
import {EditComponent} from './edit/edit.component';

@NgModule({
    imports: [
        CommonModule,
        ReactiveFormsModule,
        ShareModule,
        SaleOrderRoutingModule,
        FormsModule
    ],
    declarations: [ListComponent, CreateComponent, EditComponent]
})
export class SaleOrderModule {
}
