import { NgModule } from '@angular/core';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import { RouterModule, Routes } from '@angular/router';

const routes: Routes =
    [
        {
            path: '',
            children: [
                { path: ':specification_two', component: ListComponent },
                { path: ':specification_two/form', component: FormComponent },
                { path: ':specification_two/form/:id', component: FormComponent },
                { path: '**', redirectTo: '' }
            ]
        }
    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class SpecificationTwoRoutingModule { }
