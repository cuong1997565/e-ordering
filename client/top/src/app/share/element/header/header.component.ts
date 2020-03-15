import {Component, OnInit} from '@angular/core';
import {Select, Store} from '@ngxs/store';
import {UserState} from '../../../store/user.state';

@Component({
    selector: 'ele-header',
    templateUrl: './header.component.html',
})
export class HeaderComponent implements OnInit {
    @Select(UserState.getCurrentUser) currentUser: any;
    public customerName = '';
    constructor(private store: Store) {
    }

    ngOnInit() {
        this.currentUser.subscribe((val) => {
            if (val) {
                this.customerName = val.name;
            }
        });
    }

}
