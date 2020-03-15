import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {DefaultLayoutComponent} from './layout/default/default.component';
import {LoginLayoutComponent} from './layout/auth/auth.component';
import {AppService} from './share/app.service';
import {HttpClientModule} from '@angular/common/http';
import {ShareModule} from './share/share.module';
import {AppResolver} from './share/app.resolver';
import {ProfileResolver} from './share/profile.resolver';
import {LangResolver} from './share/lang.resolver';
import {AuthGuard} from './services/guard/auth.guard';
import {PermissionResolver} from './share/permission.resolver';
import {RoleResolver} from './share/role.resolver';

@NgModule({
    declarations: [
        AppComponent,
        DefaultLayoutComponent,
        LoginLayoutComponent,
    ],
    imports: [
        BrowserModule,
        AppRoutingModule,
        HttpClientModule,
        ShareModule
    ],
    providers: [
        AppService,
        AppResolver,
        PermissionResolver,
        RoleResolver,
        ProfileResolver,
        LangResolver,
        AuthGuard
    ],
    bootstrap: [AppComponent]
})
export class AppModule {
}
