import {AfterViewInit, Component, OnInit} from '@angular/core';
import {AppService} from './share/app.service';
import {ActivatedRoute, Router} from '@angular/router';

declare var initApp: any;

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})
export class AppComponent implements AfterViewInit {
    title = 'app';

    constructor(public app: AppService, private route: ActivatedRoute, public router: Router) {

    }

    ngOnInit() {

    }

    ngAfterViewInit() {
        // This need only load one time
        initApp.SmartActions();
    }
}


