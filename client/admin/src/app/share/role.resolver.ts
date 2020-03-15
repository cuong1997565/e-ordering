import {Injectable} from '@angular/core';
import {Resolve} from '@angular/router';
import {AppService} from './app.service';
import {DEFAULT_LANG} from '../../../../share/translation/config';
import {forkJoin} from 'rxjs';

@Injectable()
export class RoleResolver implements Resolve<any> {
    constructor(private app: AppService) {
    }

    resolve() {
        return forkJoin(
            [
                this.getRoles()
            ]);
    }

    getRoles() {
        return this.app.get('roles',{active: this.app.constant.ACTIVE_TRUE});
    }
}
