import { NgModule } from '@angular/core';
import { ListComponent } from './list/list.component';
import { FormComponent } from './form/form.component';
import {RouterModule, Routes} from '@angular/router';

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
export class SpecificationRoutingModule { }
