import { Component, OnInit } from '@angular/core';
import { AppService } from "../../../../share/app.service";
import { ActivatedRoute } from "@angular/router";
import { ListData } from "../../../../share/list-data";

@Component({
    selector: 'app-sample-type-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})

export class ListComponent implements OnInit {
    public ld;

    constructor(public app: AppService, private route: ActivatedRoute) { }

    ngOnInit() {
        this.ld = new ListData(this.app,this.route,'sample_types',{limit:3});
    }

}
