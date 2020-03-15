import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {HttpClientModule} from '@angular/common/http';
import {DefaultLayoutComponent} from './layout/default/default.component';
import {ShareModule} from './share/share.module';
import {AppService} from './share/app.service';
import {MemberLayoutComponent} from './layout/member/member.component';
import {LangRouteComponent} from './lang-route/lang-route.component';
import {AppResolver} from './share/app.resolver';
import {NgxsModule} from '@ngxs/store';
import {EntitiesState} from './store/initial_state';
import {NgxsReduxDevtoolsPluginModule} from '@ngxs/devtools-plugin';
import {NgxsLoggerPluginModule} from '@ngxs/logger-plugin';
import {ProductState} from './store/product.state';
import {ClientService} from './store/client/client.service';
import {UserState} from './store/user.state';
import {AuthService} from './module/auth/auth.service';
import {GetMeResolver} from './share/get-me.resolver';
import {LoggedInResolver} from './share/logged-in.resolver';
import {ConditionAddOrderResolver} from './module/order/condition-add-order.resolver';
import { ConditionListProductResolver } from './module/home/condition-list-product.resolver';
import {OrderState} from './store/order.state';
import {DetailOrderResolver} from './module/order/detail-order.resolver';
import {ViewsState} from './store/views/view.state';
import {OrderViewState} from './store/views/order-view.state';
import {ConditionListOrderResolver} from './module/order/condition-list-order.resolver';
import {LoadingService} from './share/loading.service';

@NgModule({
    declarations: [
        AppComponent,
        DefaultLayoutComponent,
        MemberLayoutComponent,
        LangRouteComponent,
    ],
    imports: [
        BrowserModule,
        AppRoutingModule,
        NgxsModule.forRoot([EntitiesState, ProductState, UserState, OrderState, ViewsState, OrderViewState]),
        NgxsReduxDevtoolsPluginModule.forRoot(),
        NgxsLoggerPluginModule.forRoot(),
        HttpClientModule,
        ShareModule
    ],
    providers: [AppService, LoadingService, AppResolver, ClientService, AuthService, GetMeResolver, LoggedInResolver,
        ConditionAddOrderResolver, ConditionListOrderResolver,
        ConditionListProductResolver, DetailOrderResolver],
    bootstrap: [AppComponent]
})
export class AppModule {
}
