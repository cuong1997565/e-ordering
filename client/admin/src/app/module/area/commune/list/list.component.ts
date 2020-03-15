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
    public district = {
        id: '',
        name: '',
        parent_id: '',
    };
    public url;
    public parent_id;
    public province = {
        name: '',
        id: '',
    };

    ngOnInit() {
        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_LIST_AREAS]) {
            this.router.navigate(['dashboard']);
        }
        this.parent_id = JSON.parse(localStorage.getItem('parent_id'));
        let dataQuery = {limit: 10, sort: 'name', direction: 'asc', level: 3};
        this.route.params.subscribe((e) => {
            if (e.district) {
                this.app.setConfig('district_id', e.district);
                this.app.get('areas', {'id': e.district}).subscribe((res: any) => {
                    this.district = res.data[0];
                });
            }
            if (this.parent_id) {
                dataQuery['parent_id'] = this.parent_id;
                this.app.get('areas', {'id': this.parent_id}).subscribe((res: any) => {
                    this.province = res.data[0];
                });
            }
            dataQuery['parent_id'] = e.district;
            this.ld = new ListData(this.app, this.route, 'areas', dataQuery);
        });
    }

    del(id, item) {
        this.url = 'communes/' + id + '/delete';
        this.ld.delete(this.url, item);
    }
}
