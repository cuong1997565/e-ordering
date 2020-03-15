import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';

@Component({
    selector: 'app-view',
    templateUrl: './view.component.html',
    styleUrls: ['./view.component.css']
})
export class ViewComponent implements OnInit {

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router) {
    }

    public ld;

    ngOnInit() {
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_VIEW_CREDIT_ACCOUNT]) {
            this.router.navigate(['dashboard']);
        }
        this.getListDistributor();
    }

    getListDistributor() {
        if (this.route.snapshot.params['id']) {
            this.app.get('credit-accounts', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.ld = res.data[0];
            });
        }
    }

    formatNumber(number) {
        const data = number.split('.');
        if (data[1] === '00') {
            return data[0].toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        }
        return number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }


}
