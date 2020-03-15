import { Component, OnInit } from '@angular/core';
import { AppService } from "../../../share/app.service";
import { ActivatedRoute, Router } from "@angular/router";
import { ListData } from "../../../share/list-data";
import { DEFAULT_LANG } from "../../../../../../share/translation/config";

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})

export class ListComponent implements OnInit {

    constructor(public app: AppService, private route: ActivatedRoute, private router: Router) { }

    public ld;
    public currentLang: any;
    public langs: any;

    ngOnInit() {
        this.langs =  window['listLang'];
        let listLang = window['listLang'];
        if(!this.route.snapshot.params['lang'] || Object.values(listLang).indexOf(this.route.snapshot.params['lang']) < 0){
            let lang = this.app.getConfig('LANG', DEFAULT_LANG);

            this.router.navigate(["/translation",lang])
        }

        this.currentLang = this.route.snapshot.params['lang'];
        this.getData();
    }

    getData() {
        this.ld = new ListData(this.app,this.route,'translations', {lang: this.currentLang, limit : 20, sort: 'id', direction: 'asc'});
    }

    changeLang(lang) {
        this.router.navigate(["/translation",lang], { queryParams: { page: this.route.snapshot.queryParams['page'] } });
        this.currentLang = lang;
        this.getData();
    }

}
