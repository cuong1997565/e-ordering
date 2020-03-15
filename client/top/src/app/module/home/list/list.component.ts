import { Component, OnInit } from '@angular/core';
import { AppService} from '../../../share/app.service';
import * as _ from 'lodash';
import {Actions, ofActionErrored, ofActionSuccessful, Select, Store} from '@ngxs/store';
import {GetProducts} from '../../../store/actions/products.action';
import {UserState, UserStateModel} from '../../../store/user.state';
import {Observable} from 'rxjs';
declare var $: any;

@Component({
    selector: 'app-home-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {
    @Select(UserState.getCurrentUser) users: Observable<object>;
    public listPost: any;
    constructor(public app: AppService, private store: Store, private action$: Actions) {}

    ngOnInit() {
        // this.users.subscribe(val => console.log(val));
        // this.store.dispatch(new GetProducts({page: 0, includeTotalCount: false, perPage: 1}));
        // this.action$.pipe(ofActionSuccessful(new GetProducts({page: 0, includeTotalCount: false, perPage: 1})))
        //     .subscribe(() => console.log('123'));
        // this.action$.pipe(ofActionErrored(new GetProducts({page: 0, includeTotalCount: false, perPage: 1})))
        //     .subscribe(() => console.log('123456'));
    }
}
