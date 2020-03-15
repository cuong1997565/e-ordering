import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {ListComponent} from './list/list.component';
import {AddComponent} from './add/add.component';
import {ConfirmComponent} from './confirm/confirm.component';
import {ConditionAddOrderResolver} from './condition-add-order.resolver';
import {DetailComponent} from './detail/detail.component';
import {DetailOrderResolver} from './detail-order.resolver';
import {ConditionListOrderResolver} from './condition-list-order.resolver';
import {ViewComponent} from './view/view.component';
import { SaleComponent } from './sale/sale.component';
import { DeliveryNoteComponent } from './delivery-note/delivery-note.component';

const routes: Routes =
    [
        {path: '', component: ListComponent, resolve: {condition: ConditionListOrderResolver}},

        {path: 'add', component: AddComponent, resolve: {condition: ConditionAddOrderResolver}},

        {path: 'detail/:id', component: DetailComponent, resolve: {condition: DetailOrderResolver}},

        {path: 'view/:id', component: ViewComponent, resolve: {condition: DetailOrderResolver}},

        {path: 'sale/:id', component: SaleComponent},

        {path: 'delivery-note/:id', component: DeliveryNoteComponent},

        {path: 'confirm', component: ConfirmComponent}

    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class OrderRoutingModule {
}
