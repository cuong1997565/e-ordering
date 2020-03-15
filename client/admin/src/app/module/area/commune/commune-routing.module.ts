import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {ListComponent} from './list/list.component';
import {FormComponent} from './form/form.component';

const routes: Routes =
    [
        {
            path: '',
            children: [
                {path: ':district', component: ListComponent},
                {path: ':district/form', component: FormComponent},
                {path: ':district/form/:id', component: FormComponent},
                {path: '**', redirectTo: ''}
            ]
        }
    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class CommuneRoutingModule {
}
