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
    public ulr;
    public list;
    public ListDistributor;
    public permissions = {};
    constructor(public app: AppService, private route: ActivatedRoute, public router: Router) {
    }

    ngOnInit() {
        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_LIST_CREDIT_ACCOUNTS]) {
            this.router.navigate(['dashboard']);
        }
        this.ld = new ListData(this.app, this.route, 'credit-accounts');
        this.getListDistributor();
    }

    getListDistributor() {
        this.app.get('distributors/active').subscribe((res: any) => {
            this.ListDistributor = this.app.arrToList(res.data, 'id', 'name');
        });
    }

    del(id, item) {
        this.ulr = 'credit-accounts/' + id + '/delete';
        this.ld.delete(this.ulr, item);
    }

    formatNumber(number) {
        return number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }

}
