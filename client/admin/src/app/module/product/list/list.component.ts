import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {AppService} from '../../../share/app.service';
import {ListData} from '../../../share/list-data';

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})

export class ListComponent implements OnInit {
    public ld;
    public url;
    public permissions = {};

    constructor(public app: AppService, private route: ActivatedRoute, public router: Router) {

    }

    ngOnInit() {
        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_LIST_PRODUCTS]) {
            this.router.navigate(['dashboard']);
        }
        if (this.app.curUser.group === this.app.constant.GROUP_ADMIN) {
            this.ld = new ListData(this.app, this.route, 'products');
        } else if (this.app.curUser.group === this.app.constant.GROUP_SALE_FACTORY) {
            this.ld = new ListData(this.app, this.route, 'products', {factory_id: this.app.curUser.factory_id});
        }
    }

    del(id, item) {
        this.url = 'products/' + id + '/delete';
        this.ld.delete(this.url, item);
    }

    formatPrice(price) {
        let parts = price.toString().split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        return parts[0];
        // return parts.join('.');
    }

}
