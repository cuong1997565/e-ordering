import {Injectable} from '@angular/core';
import {CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, Router} from '@angular/router';
import {Observable} from 'rxjs';
import {AppService} from './../../share/app.service';

@Injectable({
    providedIn: 'root'
})
export class AuthGuard implements CanActivate {
    constructor(public app: AppService, public router: Router) {

    }

    canActivate(
        next: ActivatedRouteSnapshot,
        state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
        // tslint:disable-next-line:radix
        if (this.app.curUser && parseInt(this.app.curUser.group) === 1) {
            return true;
        } else {
            this.router.navigate(['/dashboard']);
            return false;
        }
    }
}
