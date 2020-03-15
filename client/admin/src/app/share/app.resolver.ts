import {Injectable} from '@angular/core';
import {Resolve} from '@angular/router';
import {AppService} from './app.service';
import {DEFAULT_LANG} from '../../../../share/translation/config';
import {forkJoin} from 'rxjs';

@Injectable()
export class AppResolver implements Resolve<any> {
    constructor(private app: AppService) {
    }

    resolve() {
        return forkJoin(
            [
                this.getTrans()
            ]);
    }

    getTrans() {
        this.app.curLang = this.app.getConfig('LANG_ADMIN', DEFAULT_LANG);
        return this.app.get('translations/get_trans', {'lang': this.app.curLang});
    }
}
