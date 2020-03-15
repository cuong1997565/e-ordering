import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {ListData} from '../../../share/list-data';

@Component({
  selector: 'app-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {

    constructor(public app: AppService, private route: ActivatedRoute, public router: Router) {
    }

    public ld;
    public ListDistributor;
    public ListFactory;
    public ListPrice;
    public ListUser;
    public permissions;


    ngOnInit() {
        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_LIST_ALL_SO_ORDER]) {
            this.router.navigate(['dashboard']);
        }
        this.ld = new ListData(this.app, this.route, 'sale_orders');
        this.getListDistributor();
        this.getListFactory();
        this.getPriceList();
        this.getListUser();
    }

    getListDistributor() {
        this.app.get('distributors', {active: 1}).subscribe((res: any) => {
            this.ListDistributor = this.app.arrToList(res.data, 'id', 'name');
        });
    }

    getListFactory() {
        this.app.get('factories', {active: 1}).subscribe((res: any) => {
            this.ListFactory = this.app.arrToList(res.data, 'id', 'name');
        });
    }


    getPriceList() {
        this.app.get('price-lists', {active: 1}).subscribe((res: any) => {
            this.ListPrice = this.app.arrToList(res.data, 'id', 'name');
        });
    }

    getListUser() {
        this.app.get('users', {active: 1}).subscribe((res: any) => {
            this.ListUser = this.app.arrToList(res.data, 'id', 'name');
        });
    }

}
