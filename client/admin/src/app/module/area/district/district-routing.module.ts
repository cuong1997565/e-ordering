import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {ListComponent} from './list/list.component';
import {FormComponent} from './form/form.component';

const routes: Routes =
    [
        {
            path: '',
            children: [
                {path: ':province', component: ListComponent},
                {path: ':province/form', component: FormComponent},
                {path: ':province/form/:id', component: FormComponent},
                {path: '**', redirectTo: ''}
            ]
        }
    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class DistrictRoutingModule {
}
