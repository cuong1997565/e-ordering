import { Component, OnInit } from '@angular/core';
import { AppService } from "../../app.service";

@Component({
    selector: 'ele-flash',
    templateUrl: 'flash.component.html',
    styleUrls: ['./flash.component.css']
})

export class FlashComponent implements OnInit {

    public show = false;
    public message = '';
    public type = '';

    constructor(public app: AppService) {}

    ngOnInit() {
        let flash = this.app.getConfig('ADMIN-FLASH');
        if(flash) {
            flash = JSON.parse(flash);
            this.type = flash.type;
            this.message = flash.message;
            this.app.delConfig('ADMIN-FLASH');
            this.show = true;
        }
    }
}
