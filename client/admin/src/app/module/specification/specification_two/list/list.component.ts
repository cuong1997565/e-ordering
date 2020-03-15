import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {AppService} from '../../../../share/app.service';
import {ListData} from '../../../../share/list-data';

@Component({
    selector: 'app-dashboard-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {

    constructor(public app: AppService, private route: ActivatedRoute, public router: Router) {
    }

    public ld;
    public url;
    public specification = {
        id: '',
        name: '',
    };
    public permissions = {};

    ngOnInit() {
        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_LIST_SPECIFICATIONS]) {
            this.router.navigate(['dashboard']);
        }
        let dataQuery = {limit: 10, sort: 'name', direction: 'asc'};
        this.route.params.subscribe((e) => {
            if (e.specification_two) {
                this.app.setConfig('specification_parent_id', e.specification_two);
                dataQuery['parent_id'] = e.specification_two;
            }

            this.app.get('specifications', {'id': e.specification_two}).subscribe((res: any) => {
                this.specification = res.data[0];
            });

            this.ld = new ListData(this.app, this.route, 'specifications', dataQuery);
        });

    }

    del(id, item) {

    }

}
