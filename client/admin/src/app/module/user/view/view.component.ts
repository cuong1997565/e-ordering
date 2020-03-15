import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {AppService} from '../../../share/app.service';

@Component({
    selector: 'app-beneficiary-view',
    templateUrl: './view.component.html',
    styleUrls: ['./view.component.css']
})
export class ViewComponent implements OnInit {

    constructor
    (public app: AppService,
     private route: ActivatedRoute, public router: Router
    ) {
    }

    public user: any;

    ngOnInit() {
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_VIEW_ADMIN]) {
            this.router.navigate(['dashboard']);
        }
        this.app.get('users/detail', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
            this.user = res.data;
        });
    }
}
