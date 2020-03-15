import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent} from './list/list.component';
import {FormComponent} from './form/form.component';

const routes: Routes =
    [
        {
            path: '',
            children: [
                {path: '', component: ListComponent},
                {path: 'form', component: FormComponent},
                {path: 'form/:id', component: FormComponent},
                {path: '**', redirectTo: ''}
            ]
        }
    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})

export class ListCategoryRoutingModule {
}
