import { Component, OnInit } from '@angular/core';
import { AppService } from "../../../../share/app.service";
import { ActivatedRoute } from "@angular/router";
import { ListData } from "../../../../share/list-data";

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})

export class ListComponent implements OnInit {

    constructor(public app: AppService, private route: ActivatedRoute) { }

    public ld;

    ngOnInit() {
        this.ld = new ListData(this.app,this.route,'langs', {limit : 10, sort: 'created_at', direction: 'desc'});
    }

}
