import {Component, OnInit} from '@angular/core';
import {getSiteURL} from './share/utils/url';
import {ClientService} from './store/client/client.service';
import {Select, Selector, Store} from '@ngxs/store';
import {GetMe, LogOut} from './store/actions/users.action';
import {AppService} from './share/app.service';
import {UserState} from './store/user.state';
import {Observable} from 'rxjs';
import {AuthGuard} from './share/guard/auth.guard';
import {NavigationEnd, NavigationStart, Router} from '@angular/router';
import {OrderViewState} from './store/views/order-view.state';
import {StopLoadingDetailOrderView, StopLoadingNewOrderView, StopShowLoadingListOrderView} from './store/views/actions/order-view.action';
import {LoadingService} from './share/loading.service';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
    title = 'app';
    blurTime = new Date().getTime();
    sessionTimeout = null;
    public loading = false;
    @Select(UserState.getCurrentUser) currentUser: Observable<object>;

    constructor(private client: ClientService, private store: Store, private appService: AppService,
                private auth: AuthGuard, private router: Router, private loadingService: LoadingService) {
        this.client.setUrl(getSiteURL());
        this.client.setToken(this.appService.getConfig('E_TOKEN', ''));
    }

    ngOnInit(): void {
        this.loadingService.isLoadingApplication.subscribe((val) => {
            this.loading = val;
        });
        this.router.events.subscribe(e => {
            if (e instanceof NavigationEnd) {
                this.loadingService.stopShowLoadingApplication();
            }
            if (e instanceof NavigationStart) {
                this.loadingService.showLoadingApplication();
            }
        });
    }

    onFocusListener = () => {
        this.currentUser.subscribe((val) => {
            if (val) {
                if (Date.now() - this.blurTime > this.sessionTimeout * 60 * 10000) {
                    this.store.dispatch(new LogOut()).subscribe(() => {
                        this.auth.resetSourceAuth();
                        this.router.navigate(['/customer/login']);
                    });
                }
            }
        });

    }

    onBlurListener = () => {
        this.blurTime = new Date().getTime();
    }
}
