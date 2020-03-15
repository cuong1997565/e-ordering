import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {ListData} from '../../../../share/list-data';

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {
    public permissions = {};

    constructor(public app: AppService, private route: ActivatedRoute, public router: Router) {
    }

    public ld;
    public url;
    public parent_id;

    public category = {
        id: '',
        name: '',
    };

    public category_two = {
        id: '',
        name: '',
        parent_id: '',
    };

    public category_three = {
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
        this.parent_id = JSON.parse(localStorage.getItem('category_parent_id'));
        this.category_two = JSON.parse(localStorage.getItem('category_one_parent_id'));
        let dataQuery = {limit: 10, sort: 'name', direction: 'asc', level: 4};
        this.route.params.subscribe((e) => {
            if (e.category_three) {

                this.app.get('categories', {'id': e.category_three}).subscribe((res: any) => {
                    this.category_three = res.data[0];
                });
            }
            if (this.parent_id) {
                dataQuery['parent_id'] = this.parent_id;
                this.app.get('categories', {'id': this.parent_id}).subscribe((res: any) => {
                    this.category = res.data[0];
                });
            }
            if (this.category_two) {
                this.app.get('categories', {'id': this.category_two}).subscribe((res: any) => {
                    this.category_two = res.data[0];
                });
            }
            dataQuery['parent_id'] = e.category_three;
            this.ld = new ListData(this.app, this.route, 'categories', dataQuery);
        });
    }

}
