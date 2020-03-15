import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';
import * as $ from 'jquery';
import {UserState} from '../../../store/user.state';
import {Store} from '@ngxs/store';

@Component({
    selector: 'app-profile',
    templateUrl: './profile.component.html',
    styleUrls: ['./profile.component.css']
})
export class ProfileComponent implements OnInit {

    public fd;
    public ld;
    public customer_id;
    public permission;
    public selectRole;
    public distributor;
    public code;
    private data = {
        id: '',
        username: '',
        distributor_id: '',
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
            this.customer_id = val.id;
            this.permission = val.is_admin;
        });
    }

    ngOnInit() {
        this.fd = new FormData(this.data);
        this.getprofile();
    }

    getprofile() {
        let url = 'v1/customers/' + this.customer_id;
        this.app.get(url).subscribe((data: any) => {
            this.ld = data.data;
            this.fd.setData(data.data);
        });
    }

    save() {
        this.fd.form.value['permission'] = this.app.constant.GROUP_ADMIN;
        let url = 'v1/staffs/' + this.ld.id;
        this.app.post(url, this.fd.form.value).subscribe((data: any) => {
            $('#myModal').show();
        });
    }

    hidenModal() {
        $('.has-error').removeClass('has-error');
        $('small.help-block').remove();
        $('#myModal').hide();
        this.router.navigate(['/profile/detail']);
    }

}
