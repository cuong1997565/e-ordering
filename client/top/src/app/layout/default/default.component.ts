import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {AppService} from '../../share/app.service';
import {Store} from '@ngxs/store';
import datepickerFactory from 'jquery-datepicker';
import datepickerENFactory from 'jquery-datepicker/i18n/jquery.ui.datepicker-ja';

declare var $;

@Component({
    selector: 'app-layout-default',
    templateUrl: './default.component.html',
    styleUrls: ['./default.component.css']
})
export class DefaultLayoutComponent implements OnInit {
    private sessionTimeout = null;
    constructor(private app: AppService, private route: ActivatedRoute,
                private appService: AppService, private store: Store) {
        this.sessionTimeout = this.appService.constant.SESSION_TIMEOUT;
    }

    ngOnInit() {
        /*this.route.data.subscribe((res:any) => {

            this.app.appResolve(res.app);

            // Dev: Add this if frontend need member login
            // this.app.curMember = res.profile.data;
        })*/
        datepickerFactory($);
        datepickerENFactory($);
        $.datepicker.setDefaults( $.datepicker.regional[ "" ] );
    }

    ngAfterViewInit() {
        // Load something here
    }
}
