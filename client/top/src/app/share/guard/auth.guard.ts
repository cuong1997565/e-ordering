import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, Router} from '@angular/router';
import {forkJoin, Observable, of, ReplaySubject} from 'rxjs';
import {AuthService} from '../../module/auth/auth.service';
import {map, take} from 'rxjs/operators';
import {Actions, ofActionSuccessful, Select, Store} from '@ngxs/store';
import {UserState} from '../../store/user.state';
import {GetMe} from '../../store/actions/users.action';
import {getSiteURL} from '../utils/url';
import {ClientService} from '../../store/client/client.service';
import {AppService} from '../app.service';

@Injectable({
    providedIn: 'root'
})
export class AuthGuard implements CanActivate {
    private authSubject = new ReplaySubject<any>(1).asObservable();
    constructor(private auth: AuthService, private router: Router, private store: Store, private actions$: Actions,
                private client: ClientService, private appService: AppService) {
        this.client.setUrl(getSiteURL());
        this.client.setToken(this.appService.getConfig('E_TOKEN', ''));
        // this.authSubject = this.store.dispatch(new GetMe());
    }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
        return this.store.dispatch(new GetMe()).pipe(
            map(global_state => {
                const currentUserId = global_state.entities.users.currentUserId;
                if (global_state.entities.users.profiles[currentUserId]) {
                    if (state.url.indexOf('/login') !== -1 || state.url.indexOf('/forgot-password') !== -1) {
                        this.router.navigate(['/']);
                        return false;
                    }
                    return true;
                } else {
                    if (state.url.indexOf('/login') !== -1 || state.url.indexOf('/forgot-password') !== -1) {
                        return true;
                    }
                    this.router.navigate(['customer/login'], { queryParams: {redirect_to: encodeURIComponent(state.url)}});
                    return false;
                }
            })
        );
    }

    public resetSourceAuth() {
        // Because we use ReplaySubject, we need clear old buffer after login or logout.
        // Because if not, this source still emit old value. (old user logged in)
        this.authSubject = this.store.dispatch(new GetMe());
    }
}
