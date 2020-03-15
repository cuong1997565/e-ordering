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
    public permissions = {};
    constructor(public app: AppService, private route: ActivatedRoute, public router: Router) {
    }

    public ld;
    public url;
    public category = {
        id: '',
        name: '',
    };

    ngOnInit() {
        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_LIST_CATEGORIES]) {
            this.router.navigate(['dashboard']);
        }
        let dataQuery = {limit: 10, sort: 'name', direction: 'asc', level: 2};
        this.route.params.subscribe((e) => {
            if (e.category) {
                this.app.setConfig('category_parent_id', e.category);
                dataQuery['parent_id'] = e.category;
            }

            this.app.get('categories', {'id': e.category}).subscribe((res: any) => {
                this.category = res.data[0];
            });

            this.ld = new ListData(this.app, this.route, 'categories', dataQuery);
        });

    }

    del(id, item) {
        this.url = 'districts/' + id + '/delete';
        this.ld.delete(this.url, item);
    }

}
