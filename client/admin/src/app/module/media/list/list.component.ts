import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from "@angular/platform-browser";
import { AppService } from "../../../share/app.service";

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {

    constructor(public app: AppService,private sanitizer:DomSanitizer) { }

    public url;

    ngOnInit() {
        this.url = this.sanitizer.bypassSecurityTrustResourceUrl(this.app.constant.BASE_WEB+'media/dialog.php?type=0&akey='+this.app.authToken);
    }
}
