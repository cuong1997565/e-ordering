import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {ListComponent} from './list/list.component';
import {FormComponent} from './form/form.component';
import {ProfileComponent} from './profile/profile.component';
import {PasswordComponent} from './password/password.component';
import {ViewComponent} from './view/view.component';
import {RoleResolver} from '../../share/role.resolver';
import {ProfileResolver} from '../../share/profile.resolver';

const routes: Routes =
    [
        {
            path: '',
            children:
                [
                    {path: '', redirectTo: 'list'},
                    {path: 'list', component: ListComponent, resolve: {profile: ProfileResolver}},
                    {path: 'detail/:id', component: ViewComponent},
                    {path: 'form', component: FormComponent, resolve: {roles: RoleResolver, profile: ProfileResolver}},
                    {path: 'form/:id', component: FormComponent, resolve: {roles: RoleResolver, profile: ProfileResolver}},
                    {path: 'profile', component: ProfileComponent},
                    {path: 'password', component: PasswordComponent}
                ]
        }
    ];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class UserRoutingModule {
}
