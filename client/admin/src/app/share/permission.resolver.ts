import {Injectable} from '@angular/core';
import {Resolve} from '@angular/router';
import {AppService} from './app.service';
import {DEFAULT_LANG} from '../../../../share/translation/config';
import {forkJoin} from 'rxjs';

@Injectable()
export class PermissionResolver implements Resolve<any> {
    constructor(private app: AppService) {
    }

    resolve() {
        return forkJoin(
            [
                this.getPermissions()
            ]);
    }

    getPermissions() {
        return this.app.get('permissions');
    }
}
