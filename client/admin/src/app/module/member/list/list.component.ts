import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {ListData} from '../../../share/list-data';

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})

export class ListComponent implements OnInit {

    public ld;
    public ListDistributor;
    public permissions = {};

    constructor(public app: AppService, private route: ActivatedRoute, public router: Router) {
    }

    ngOnInit() {
        // const permissions = {};
        // // convert permission array to object and check roles
        // const object = Object.assign({}, this.app.curUser.role.permissions);
        //
        // for (const [key, value] of Object.entries(object)) {
        //     // @ts-ignore
        //     permissions[value] = value;
        // }

        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }

        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_LIST_MEMBERS]) {
            this.router.navigate(['dashboard']);
        }

        this.ld = new ListData(this.app, this.route, 'customers', {
            limit: this.app.constant.LIMIT_USER_MANAGEMENT,
            sort: 'name',
            direction: this.app.constant.ORDER_ASC,
            admin: true,
            is_admin: this.app.constant.ACCOUNT_HOLDER,
        });
        this.getListDistributor();
    }

    getListDistributor() {
        this.app.get('distributors', {active : this.app.constant.ACTIVE_TRUE}).subscribe((res: any) => {
            this.ListDistributor = this.app.arrToList(res.data, 'id', 'name');
        });
    }

}
