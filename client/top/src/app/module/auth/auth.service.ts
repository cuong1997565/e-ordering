import {Injectable} from '@angular/core';
import {Select, Store} from '@ngxs/store';
import {UserState} from '../../store/user.state';
import {Observable} from 'rxjs';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class AuthService {
    @Select(UserState.getCurrentUser) currentUser: Observable<object>;
    constructor(public store: Store) {}
    //
    isAuthenticated(): Observable<boolean> {
        return this.currentUser.pipe(
            map(val => {
                if (!val) {
                    return false;
                }
                return true;
            })
        );
    }
}
