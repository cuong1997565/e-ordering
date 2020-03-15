import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {AppService} from '../../../share/app.service';
import {ListData} from '../../../share/list-data';
import * as _ from 'lodash';
import * as $ from 'jquery';

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {
    public ld;
    public url;
    public permissions = {};

    constructor(
        public app: AppService,
        private route: ActivatedRoute,
        private router: Router) {
    }

    ngOnInit() {
        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_LIST_ORDERS]) {
            this.router.navigate(['dashboard']);
        }
        if (this.app.curUser.group === 3) {
            this.ld = new ListData(this.app, this.route, 'orders', {factory_id: this.app.curUser.factory_id});
        } else {
            this.ld = new ListData(this.app, this.route, 'orders');
        }
    }

    del(id, item) {

    }

    directSo(id) {
        const url = 'get_order_about_sale_order/' + id;
        this.app.get(url).subscribe((res) => {
            // @ts-ignore
            return this.router.navigate([`sale-order/edit`, res.data.id]);
        });
    }

}
