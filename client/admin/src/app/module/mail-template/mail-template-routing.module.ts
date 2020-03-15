import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ListComponent } from "./list/list.component";

const routes: Routes =
[
    {
        path: '',
        children: [
            { path: '', component: ListComponent },
            { path: '**', redirectTo: '' }
        ]
    }
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class MailTemplateRoutingModule { }
