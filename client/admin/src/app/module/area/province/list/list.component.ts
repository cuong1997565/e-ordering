import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {AppService} from '../../../../share/app.service';
import {ListData} from '../../../../share/list-data';

@Component({
    selector: 'app-city-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {
    public permissions = {};
    constructor(public app: AppService, private route: ActivatedRoute, public router: Router) {
    }

    public ld;
    public ulr;

    ngOnInit() {
        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_LIST_AREAS]) {
            this.router.navigate(['dashboard']);
        }
        this.ld = new ListData(this.app, this.route, 'areas', {limit: 10, sort: 'name', direction: 'asc', level: 1});
    }

    del(id, item) {
        this.ulr = 'provinces/' + id + '/delete';
        this.ld.delete(this.ulr, item);
    }

}
