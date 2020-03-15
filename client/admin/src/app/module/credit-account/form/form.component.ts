import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';
import * as $ from 'jquery';

@Component({
    selector: 'app-form',
    templateUrl: './form.component.html',
    styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router) {
    }

    public ListDistributor;
    public distributor1;
    public distributor2;
    public distributor3;
    public fd;
    private data = {
        id: '',
        distributor_id: null,
        amount: 0,
        hold_amount: 0,
        available_amount: 0,
        credit_limit: '',
    };

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_CREDIT_ACCOUNT]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_CREDIT_ACCOUNT]) {
            this.router.navigate(['dashboard']);
        }
        this.app.get('distributors/checkCreditAccount').subscribe((res: any) => {
            this.ListDistributor = this.app.arrToList(res.data, 'id', 'name');
            this.distributor1 = res.data;
        });
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('credit-accounts', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);

                this.distributor2 = res.data[0].distributor;
                if (this.distributor2) {
                    setTimeout(() => {
                        this.distributor1.push(this.distributor2);
                        this.distributor3 = this.app.arrToList(this.distributor1, 'id', 'name');
                    }, 500);
                }
            });
        }
    }

    save() {
        let url = 'credit-accounts/' + this.route.snapshot.params['id'];
        if (this.route.snapshot.params['id']) {
            this.app.post(url, this.fd.form.value).subscribe((res: any) => {
                this.app.flashSuccess('Credit Account has been saved');
                this.router.navigate([`/credit-account`]);
            });
        } else {
            this.app.post('credit-accounts', this.fd.form.value).subscribe((res: any) => {
                this.app.flashSuccess('Credit Account has been saved');
                this.router.navigate([`/credit-account`]);
            });
        }
    }

}
