import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';
import * as $ from 'jquery';
import {UserState} from '../../../store/user.state';
import {Store} from '@ngxs/store';

@Component({
    selector: 'app-form',
    templateUrl: './form.component.html',
    styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

    public fd;
    public distributor_id;
    public permission;
    public selectRole;
    public distributor;
    public code;
    public check: boolean = false;
    private data = {
        id: '',
        distributor_id: '',
        username: '',
        name: '',
        telephone: '',
        password: '',
        password_confirmation: '',
        email: '',
        active: 1,
        is_admin: '',
        type: 0
    };
    public role = [
        {id: 3, name: 'Order Manager'},
        {id: 2, name: 'Order Viewer'},
    ];

    constructor(
        public app: AppService,
        private route: ActivatedRoute,
        private router: Router,
        private store: Store,
    ) {
        this.store.select(UserState.getCurrentUser).subscribe((val) => {
            this.distributor_id = val.distributor_id;
            this.permission = val.is_admin;
        });
    }

    ngOnInit() {
        this.fd = new FormData(this.data);
        this.app.get('v1/distributors', {id: this.distributor_id}).subscribe((data: any) => {
            this.distributor = data.data[0];
        });
        if (this.route.snapshot.params['id']) {
            $('#username').attr('disabled', 'disabled');
            this.check = true;
            let url = 'v1/customers/' + this.route.snapshot.params['id'];
            this.fd.isNew = false;
            this.app.get(url, {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data);
                this.data.is_admin = res.data.is_admin;
                this.selectRole = res.data.is_admin;
            });
        }

    }

    save() {
        this.fd.form.value['distributor_id'] = this.distributor_id;
        this.fd.form.value['permission'] = this.permission;
        this.fd.form.value.is_admin = this.data.is_admin;
        let url = 'v1/staffs/' + this.route.snapshot.params['id'];
        if (this.route.snapshot.params['id']) {
            this.app.post(url, this.fd.form.value).subscribe((data: any) => {
                $('#myModal').show();
            });
        } else {
            this.fd.form.value.active = 0;
            this.app.post('v1/staffs', this.fd.form.value).subscribe((data: any) => {
                $('#myModal').show();
            });
        }
    }

    hidenModal() {
        // this.fd = new FormData(this.data);
        // $('.has-error').removeClass('has-error');
        // $('small.help-block').remove();
        // $(':input').val('');
        // $('#myModal').hide();
        this.router.navigate(['/staff/list']);
    }

    changeRole(e) {
        this.data.is_admin = e;
    }

}
