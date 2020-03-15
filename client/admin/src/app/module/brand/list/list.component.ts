import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {AppService} from '../../../share/app.service';
import {ListData} from '../../../share/list-data';

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {
    public ld;
    public ulr;
    public permissions = {};
    constructor(public app: AppService, private route: ActivatedRoute) {
    }

    ngOnInit() {
        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        this.ld = new ListData(this.app, this.route, 'brands');
    }

    del(id, item) {
        this.ulr = 'brands/' + id + '/delete';
        this.ld.delete(this.ulr, item);
    }

}
