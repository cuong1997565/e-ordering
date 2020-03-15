import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {DefaultLayoutComponent} from './layout/default/default.component';
import {MemberLayoutComponent} from './layout/member/member.component';
import {AppResolver} from './share/app.resolver';
import {AuthGuard} from './share/guard/auth.guard';
import {GetMeResolver} from './share/get-me.resolver';
import {LoggedInResolver} from './share/logged-in.resolver';

const routes: Routes =
    [
        {
            path: 'customer',
            component: MemberLayoutComponent,
            children:
                [
                    {path: '', loadChildren: './module/auth/auth.module#AuthModule'},
                ],
            // resolve: {app: AppResolver }
        },
        {
            path: '',
            component: DefaultLayoutComponent,
            children:
                [
                    {path: '', loadChildren: './module/home/home.module#HomeModule', resolve: {loggedIn: LoggedInResolver}},

                    {path: 'catalog', loadChildren: './module/catalog/catalog.module#CatalogModule', resolve: {loggedIn: LoggedInResolver}},

                    {path: 'order', loadChildren: './module/order/order.module#OrderModule', resolve: {loggedIn: LoggedInResolver}},

                    // Additional modules
                    {
                        path: 'staff', loadChildren: './module/staff/staff.module#StaffModule', resolve: {loggedIn: LoggedInResolver},
                    },

                    {
                        path: 'profile', loadChildren: './module/profile/profile.module#ProfileModule', resolve: {loggedIn: LoggedInResolver},
                    },

                    // Default modules
                    {path: '**', redirectTo: ''}
                ],
            resolve: {getMe: GetMeResolver}
        }
    ];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})
export class AppRoutingModule {
}
