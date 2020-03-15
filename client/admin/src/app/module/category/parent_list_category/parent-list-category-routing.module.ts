import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ListComponent} from './list/list.component';
import {FormComponent} from './form/form.component';

const routes: Routes =
    [
        {
            path: '',
            children: [
                { path: ':category', component: ListComponent },
                { path: ':category/form', component: FormComponent },
                { path: ':category/form/:id', component: FormComponent },
                { path: '**', redirectTo: '' }
            ]
        }
    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class ParentListCategoryRoutingModule {
}
