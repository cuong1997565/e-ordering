import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {FormComponent} from './form/form.component';
import {ListComponent} from './list/list.component';

const routes: Routes =
    [
        {path: '', redirectTo: 'list'},
        {path: 'list', component: ListComponent},
        {path: 'form', component: FormComponent},
        {path: 'form/:id', component: FormComponent},

    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class StaffRoutingModule {
}
