import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import { AppService } from '../../../../share/app.service';
import { ListData } from '../../../../share/list-data';

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {

    constructor(public app: AppService, private route: ActivatedRoute, public router: Router) {
    }

    public ld;
    public ulr;
    public permissions = {};

    ngOnInit() {
        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_LIST_SPECIFICATIONS]) {
            this.router.navigate(['dashboard']);
        }
        this.ld = new ListData(this.app, this.route, 'specifications', {limit: 10, sort: 'name', direction: 'asc', parent_id: 0});
    }

    del(id, item) {
    }


}
