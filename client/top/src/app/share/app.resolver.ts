import {Injectable} from '@angular/core';
import {Resolve} from '@angular/router';
import {AppService} from './app.service';
import {forkJoin} from 'rxjs';
import {Store} from '@ngxs/store';
import {GetMe} from '../store/actions/users.action';

@Injectable()
export class AppResolver implements Resolve<any> {
    constructor(private app: AppService, private store: Store) {
    }

    resolve() {
        return forkJoin(
            [
                this.getProfile()
            ]);
    }

    getProfile() {
        return Promise.resolve();
        // return this.app.get('customer/me/profile');
    }
}
