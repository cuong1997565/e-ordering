import {Injectable} from '@angular/core';
import {ActivatedRouteSnapshot, Resolve} from '@angular/router';
import {AppService} from './app.service';
import {forkJoin} from 'rxjs';
import {Store} from '@ngxs/store';
import {GetMe} from '../store/actions/users.action';

@Injectable()
export class GetMeResolver implements Resolve<any> {
    constructor(private app: AppService, private store: Store) {
    }

    resolve(route: ActivatedRouteSnapshot) {
        return forkJoin(
            [
                this.getProfile()
            ]);
    }

    getProfile() {
        return this.store.dispatch(new GetMe());
        // return Promise.resolve();
        // return this.app.get('customer/me/profile');
    }

}
