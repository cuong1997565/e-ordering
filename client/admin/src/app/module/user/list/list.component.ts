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
    public permissions = {};
    constructor(public app: AppService, private route: ActivatedRoute, public router: Router) {
    }

    public group_list = [];

    ngOnInit() {
        // const permissions = {};
        // // convert permission array to object and check roles
        // const object = Object.assign({}, this.app.curUser.role.permissions);
        //
        // for (const [key, value] of Object.entries(object)) {
        //     // @ts-ignore
        //     permissions[value] = value;
        // }
        // console.log(this.app.curUser.role.permissions);
        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_LIST_ADMINS]) {
            this.router.navigate(['dashboard']);
        }
        let group_admin = this.app.constant.User.group;
        delete group_admin[2];
        this.group_list = group_admin;

        this.ld = new ListData(this.app, this.route, 'users', {
            limit: this.app.constant.LIMIT_USER_MANAGEMENT,
            sort: 'username',
            direction: this.app.constant.ORDER_ASC,
            admin: true
        });
    }

}
