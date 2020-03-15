import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ListComponent } from "./list/list.component";
import { FormComponent } from "./form/form.component";
import { GenComponent } from "./gen/list.component";
import { DevComponent } from "./dev/dev.component";

const routes: Routes =
[
    {
        path: '',
        children: [
            { path: 'lang', loadChildren: './lang/lang.module#LangModule'  },
            { path: 'gen', component: GenComponent },
            { path: 'dev/:lang', component: DevComponent },
            { path: 'dev', component: DevComponent },
            { path: 'form', component: FormComponent },
            { path: 'form/:lang', component: FormComponent },
            { path: 'form/:lang/:id', component: FormComponent },
            { path: '', component: ListComponent },
            { path: ':lang', component: ListComponent },
            { path: '**', redirectTo: '' }
        ]
    }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TranslationRoutingModule { }
