import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {AppService} from '../../share/app.service';

@Component({
    selector: 'app-layout-login',
    templateUrl: './auth.component.html',
    styleUrls: ['./auth.component.css']
})
export class LoginLayoutComponent implements OnInit {

    constructor(private app: AppService, private route: ActivatedRoute) {
    }

    ngOnInit() {
        this.route.data.subscribe((res: any) => {
            this.app.langResolve(res.lang.data);
            this.app.appResolve(res.app);
        });
    }
}
