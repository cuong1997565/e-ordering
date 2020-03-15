import { Component, OnInit } from '@angular/core';
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

    public fd;
    public url;
    public creditAccount;
    private data = {
        id: '',
        credit_id: null,
        amount: '',
        description: '',
        reference: '',
        address: '',
        is_manual: 1,
        is_hold: 0,
        transaction_type: null
    };

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router
    ) { }

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
        }
        this.listCreditAccount();
    }

    listCreditAccount() {
        this.app.get('credit-account-distributor').subscribe((res: any) => {
            this.creditAccount = this.app.arrToList(res.data, 'id', 'name');
        });
    }

    save() {
        this.app.post('credit-transactions', this.fd.form.value).subscribe((data: any) => {
            this.app.flashSuccess('Credit transactions has been saved');
            return this.router.navigate(['/credit-transaction']);
        });
    }

}
