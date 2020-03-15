import { Component, OnInit } from '@angular/core';
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
    public  url;
    public permissions;

    constructor(public app: AppService, private route: ActivatedRoute, public router: Router) { }

    ngOnInit() {
        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_LIST_ROLES]) {
            this.router.navigate(['dashboard']);
        }
        this.ld = new ListData(this.app, this.route, 'roles');
    }
    del(id, item) {
        this.url = 'roles/' + id + '/delete';
        this.ld.delete(this.url, item);
    }

    customString(permission) {
        return permission.toString().replace(/_/g, ' ');
    }
}