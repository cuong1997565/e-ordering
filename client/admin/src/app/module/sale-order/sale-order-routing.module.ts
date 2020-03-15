import { NgModule } from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {ListComponent} from './list/list.component';
import {CreateComponent} from './create/create.component';
import {EditComponent} from './edit/edit.component';

const routes: Routes =
    [
        {
            path: '',
            children:
                [
                    { path: '', redirectTo: 'list'},
                    { path: 'list', component: ListComponent },
                    { path: 'form', component: CreateComponent },
                    { path: 'edit/:id', component: EditComponent}
                ]
        }
    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class SaleOrderRoutingModule { }
