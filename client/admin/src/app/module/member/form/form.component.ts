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

    constructor(
        public app: AppService,
        private route: ActivatedRoute,
        private router: Router
    ) {
    }

    public fd;
    public distributor;
    public distributor1;
    public distributor2;
    public distributor3;
    private data = {
        id: '',
        distributor_id: null,
        name: '',
        username: '',
        password: '',
        password_confirmation: '',
        email: '',
        telephone: '',
        is_admin: 1,
        active: 0,
        type: 0
    };

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_MEMBER]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_MEMBER]) {
            this.router.navigate(['dashboard']);
        }
        this.app.get('distributors/checkCustomer', {active: this.app.constant.ACTIVE_TRUE}).subscribe((res: any) => {
            this.distributor = this.app.arrToList(res.data, 'id', 'name');
            this.distributor1 = res.data;
        });
        if (this.route.snapshot.params['id']) {
            $('#username').attr('disabled', 'disabled');
            this.fd.isNew = false;
            this.app.get('customers/form', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data);
                this.distributor2 = res.data.distributor;
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
        this.app.post('customers/admin/form', this.fd.form.value).subscribe((data: any) => {
            this.app.flashSuccess('Member has been saved');

            if (this.app.curUser.id === this.fd.form.get('id').value && this.fd.form.get('active').value === 0) {
                this.app.delConfig('AUTH_TOKEN');
                this.router.navigate(['/auth']);
            }
            if (this.app.curUser.group === this.app.constant.GROUP_ADMIN) {
                this.router.navigate(['/member']);
            }
        });
    }
}
