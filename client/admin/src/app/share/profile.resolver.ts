import {Injectable} from '@angular/core';
import {Resolve} from '@angular/router';
import {AppService} from './app.service';
import {map} from 'rxjs/operators';

@Injectable()
export class ProfileResolver implements Resolve<any> {
    constructor(private app: AppService) {
    }

    resolve() {
        return this.app.get('users/profile').pipe(
            map((val: any) => {
                if (val.data.role) {
                    const permissions = val.data.role.permissions;
                    const newObject = {};
                    for (let i = 0; i < permissions.length; i++) {
                        newObject[permissions[i]] = permissions[i];
                    }
                    val.data.role.permissions = newObject;
                }
                return val;
            })
        );
    }
}
