import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';
import * as $ from 'jquery';

@Component({
    selector: 'app-user-form',
    templateUrl: './form.component.html',
    styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

    public fd;
    public profile = null;
    public roleId = null;
    public code;
    public check;
    public factory;
    public roles = {};
    private data = {
        id: '',
        factory_id: null,
        name: '',
        username: '',
        password: '',
        password_confirmation: '',
        email: '',
        phone_number: '',
        group: '',
        active: 0,
        roles: ''
    };

    constructor(
        public app: AppService,
        private route: ActivatedRoute,
        private router: Router
    ) {
    }

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_ADMIN]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_ADMIN]) {
            this.router.navigate(['dashboard']);
        }
        this.roles = this.route.snapshot.data.roles[0].data;
        this.roles = this.app.arrToList(this.roles, 'name', 'display_name');
        let group_admin = this.app.constant.User.group;
        delete group_admin[2];
        this.fd.group = group_admin;

        this.app.get('factories').subscribe((res: any) => {
            this.factory = res.data;
        });
        if (this.route.snapshot.params['id']) {
            $('#username').attr('disabled', 'disabled');
            this.fd.isNew = false;
            this.app.get('users/form', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data);

                this.profile = res.data;
                if (this.profile.roles) {
                    this.roleId = this.profile.roles.name;
                }
                this.check = res.data.factory_id;
                this.code = 10;
            });
        }
    }

    save() {
        if (this.code == 1) {
            this.fd.form.value['factory_id'] = null;
        }
        this.app.post('users/form', this.fd.form.value).subscribe((data: any) => {
            this.app.flashSuccess('Admin has been saved');

            if (this.app.curUser.id === this.fd.form.get('id').value && this.fd.form.get('active').value == 0) {
                this.app.delConfig('AUTH_TOKEN');
                this.router.navigate(['/auth']);
            }
            if (this.app.curUser.group === this.app.constant.GROUP_ADMIN) {
                this.router.navigate(['/user']);
            }
        });
    }

    change(e) {
        this.code = e.target.value;
        $('#factory option[value=null]').attr('selected', 'selected');
        $('#factory1 option[value=null]').attr('selected', 'selected');
        $('#factory2 option[value=null]').attr('selected', 'selected');
    }

}
