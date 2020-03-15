import {Injectable} from '@angular/core';
import {ActivatedRouteSnapshot, Resolve, Router, RouterStateSnapshot} from '@angular/router';
import {AppService} from './app.service';
import {EMPTY} from 'rxjs';
import {Store} from '@ngxs/store';
import {UserState} from '../store/user.state';
import {constant} from '../config';

@Injectable()
export class LoggedInResolver implements Resolve<any> {
    constructor(private app: AppService, private store: Store, private router: Router) {
    }

    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        this.store.select(UserState.getCurrentUser).subscribe((val) => {
            if (!val) {
                if (state.url.indexOf('/login') !== -1 || state.url.indexOf('/forgot-password') !== -1) {
                    return EMPTY;
                }
                this.router.navigate(['customer/login'], {queryParams: {redirect_to: encodeURIComponent('/order')}});
                return EMPTY;
            }
            // else {
            //     if (state.url.indexOf('/staff') !== -1 && val.is_admin === constant.ORDER_MANAGER) {
            //         this.router.navigate(['/order']);
            //         return EMPTY;
            //     }
            //     if (state.url.indexOf('/staff') !== -1 && val.is_admin === constant.ORDER_VIEWER) {
            //         this.router.navigate(['/order']);
            //         return EMPTY;
            //     }
            // }
        });
        return EMPTY;
    }
}
