import { Component, OnInit } from '@angular/core';
import { Router } from "@angular/router";
import { AppService } from "../../app.service";
declare var $: any;

@Component({
    selector: 'ele-user-logout',
    templateUrl: './logout.component.html'
})
export class UserLogoutComponent implements OnInit {

    constructor(public app: AppService,private router: Router) { }

    ngOnInit() {
    }

    popup(){
        $.SmartMessageBox({
            title : "<i class='fa fa-sign-out txt-color-orangeDark'></i> Logout <span class='txt-color-orangeDark'><strong>" + $('#show-shortcut').text() + "</strong></span> ?",
            content : "You can improve your security further after logging out by closing this opened browser",
            buttons : '[No][Yes]'

        }, (ButtonPressed) => {
            if (ButtonPressed == "Yes") {
                this.logout()
            }
        });
    }

    logout(){
        this.app.delConfig('AUTH_TOKEN');
        this.router.navigate(['/auth'])
    }
}
