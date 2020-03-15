import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { FormComponent} from "./form/form.component";
import { ListComponent} from "./list/list.component";

const routes: Routes = 
[
    {
        path: '',
        children: [
            { path: '', component: ListComponent },
            { path: 'form', component: FormComponent },
            { path: 'form/:id', component: FormComponent },
            { path: '**', redirectTo: '' }
        ]
    }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SampleTypeRoutingModule { }
