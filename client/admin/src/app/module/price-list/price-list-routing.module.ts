import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
// @ts-ignore
import { ListComponent } from './list/list.component';
import {FormComponent} from './form/form.component';
import {PriceListItemComponent} from './price-list-item/price-list-item.component';

const routes: Routes =
    [
        {
            path: '',
            children:
                [
                    { path: '', redirectTo: 'list' },
                    { path: 'list', component: ListComponent },
                    { path: 'form', component: FormComponent },
                    { path: 'form/:id', component: FormComponent},
                    { path: 'list-item/:id', component: PriceListItemComponent }
                ]
        }
    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class PriceListRoutingModule { }

