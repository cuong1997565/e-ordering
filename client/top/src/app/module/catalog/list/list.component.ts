import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {AppService} from '../../../share/app.service';
import {ListData} from '../../../share/list-data';
import * as $ from 'jquery';

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {

    public ld;

    constructor(public app: AppService, private route: ActivatedRoute, private router: Router) {
    }

    ngOnInit() {
        this.ld = new ListData(this.app, this.route,
            'v1/catalogs',
            {
                limit: this.app.constant.LIMIT_USER_MANAGEMENT,
                sort: 'created_at',
                direction: this.app.constant.ORDER_ASC
            });
    }
    goDownload(file, name) {
        let url = this.app.constant.BASE_API + 'v1/catalogs/filePath?url=' + file + '&name=' + name;
        return window.location.href = url;
    }
}
