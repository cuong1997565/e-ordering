import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
// @ts-ignore
import { ListComponent } from './list/list.component';
import {FormComponent} from './form/form.component';

const routes: Routes =
    [
        {
            path: '',
            children:
                [
                    { path: '', redirectTo: 'list' },
                    { path: 'list', component: ListComponent },
                    { path: 'detail/:id', component: FormComponent },
                ]
        }
    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class OrderRoutingModule { }

