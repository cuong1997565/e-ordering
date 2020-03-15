import { Component, OnInit } from '@angular/core';
import { AppService } from "../../../share/app.service";
import {ActivatedRoute, Router} from "@angular/router";
import { ListData } from "../../../share/list-data";
import {DEFAULT_LANG} from "../../../../../../share/translation/config";

@Component({
    selector: 'app-list',
    templateUrl: './gen.component.html',
})

export class GenComponent implements OnInit {

    constructor(public app: AppService, private route: ActivatedRoute, private router: Router) { }

    public ld;
    public currentLang: any;

    ngOnInit() {
        this.app.get("translations/gen").subscribe((res:any) => {
            this.router.navigate(['/translation'])
        })
    }
}
