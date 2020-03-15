import {Component, OnInit} from '@angular/core';
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
    public url;
    public listFactories;

    private data = {
        id: '',
        code: '',
        name: '',
        factory_id: null,
        active: 0
    };

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router) {
    }

    ngOnInit() {
        this.listFactory();
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_STORE]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_STORE]) {
            this.router.navigate(['dashboard']);
        }
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('stores', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
                this.data = res.data[0];
            });

        }

    }

    listFactory() {
        this.app.get('factories', {active: this.app.constant.ACTIVE_TRUE}).subscribe((res: any) => {
            this.listFactories = this.app.arrToList(res.data, 'id', 'name');
        });
    }

    save() {
        this.url = 'stores/' + this.route.snapshot.params['id'];
        this.fd.form.value.active = this.fd.form.value.active ? 1 : 0;
        if (this.route.snapshot.params['id']) {
            this.app.post(this.url, this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Store has been saved');
                return this.router.navigate(['/store']);
            });
        } else {
            this.app.post('stores', this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Store has been saved');
                return this.router.navigate(['/store']);
            });
        }
    }
}
