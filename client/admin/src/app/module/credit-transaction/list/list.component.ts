import { Component, OnInit } from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute} from '@angular/router';
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
    constructor(public app: AppService, private route: ActivatedRoute) {
    }

    ngOnInit() {
        if (this.app.curUser.role) {
            this.permissions = this.app.curUser.role.permissions;
        }
        this.ld = new ListData(this.app, this.route, 'credit-transactions');
    }

    formatNumber(number) {
        const data = number.split('.');
        if (data[1] === '00') {
            return data[0].toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        }
        return number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }


}
