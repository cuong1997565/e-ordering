import { Component, OnInit } from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

    public fd;
    public listUoms: any = [];
    public url;
    public permissions = [];
    private selectedIds = {};
    private oldPermissions = {};
    private data = {
        id: [],
        name: '',
        display_name: '',
        description: '',
        active: 0

    };

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router
    ) { }

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_ROLE]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_ROLE]) {
            this.router.navigate(['dashboard']);
        }
        this.permissions = this.route.snapshot.data.permissions[0].data;
        if (this.route.snapshot.params['id']) {
            this.app.get('roles/' + this.route.snapshot.params['id']).subscribe((data: any) => {
                const permissions = data.data.permissions;
                for (let i = 0; i < permissions.length; i++) {
                    this.oldPermissions[permissions[i]] = permissions[i];
                }
                this.selectedIds = this.oldPermissions;
                this.fd.setData(data.data);
            });
            this.fd.isNew = false;
        }
        console.log(this.permissions);
    }

    getCheckbox(id, $event) {
        if ($event.target.checked) {
            this.selectedIds[id] = id;
        } else {
            delete this.selectedIds[id];
        }
    }
    save() {
        this.fd.form.value.permissions = Object.values(this.selectedIds);

        this.fd.form.value.active = this.fd.form.value.active ? 1 : 0;

        if (this.route.snapshot.params['id']) {
            this.url = 'roles/' + this.route.snapshot.params['id'];
            this.app.post(this.url, this.fd.form.value).subscribe((val) => {
                window.location.href = 'roles';
            });
        } else {
            this.app.post('roles', this.fd.form.value).subscribe((val) => {
                window.location.href = 'roles';
            });
        }
    }

}
