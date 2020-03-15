import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {ListComponent} from './list/list.component';
import {CreateComponent} from './create/create.component';
import {ReverseComponent} from './reverse/reverse.component';

const routes: Routes =
    [
        {
            path: '',
            children:
                [
                    {path: '', redirectTo: 'list'},
                    {path: 'list', component: ListComponent},
                    {path: 'form', component: CreateComponent},
                    {path: 'form/:id', component: CreateComponent},
                    {path: 'reverse/:id', component: ReverseComponent},
                ]
        }
    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class DeliveryNoteRoutingModule {
}
