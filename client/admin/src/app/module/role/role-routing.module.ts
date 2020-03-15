import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
// @ts-ignore
import { ListComponent } from './list/list.component';
import {FormComponent} from './form/form.component';
import {PermissionResolver} from '../../share/permission.resolver';
import {ProfileResolver} from '../../share/profile.resolver';

const routes: Routes =
    [
        {
            path: '',
            children:
                [
                    { path: '', redirectTo: 'list' },
                    { path: 'list', component: ListComponent },
                    { path: 'form/:id', component: FormComponent, resolve: { permissions: PermissionResolver, profile: ProfileResolver }},
                    { path: 'form', component: FormComponent, resolve: { permissions: PermissionResolver, profile: ProfileResolver }},
                ]
        }
    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})

export class RoleRoutingModule { }

