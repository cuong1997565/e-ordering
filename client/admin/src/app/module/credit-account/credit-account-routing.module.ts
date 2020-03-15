import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {ListComponent} from './list/list.component';
import {FormComponent} from './form/form.component';
import {ViewComponent} from './view/view.component';

const routes: Routes =
    [
        {
            path: '',
            children:
                [
                    {path: '', redirectTo: 'list'},
                    {path: 'list', component: ListComponent},
                    {path: 'form', component: FormComponent},
                    {path: 'form/:id', component: FormComponent},
                    {path: 'view/:id', component: ViewComponent},
                ]
        }
    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class CreditAccountRoutingModule {
}
