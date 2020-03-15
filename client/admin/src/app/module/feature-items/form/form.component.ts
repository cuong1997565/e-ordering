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
    public url;
    public listFeatures: any = [];
    private data = {
        id: '',
        name: '',
        feature_id: null,
        is_active: 0,
        display_name: '',
        active: 0

    };

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router
    ) { }

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_FEATURE_ITEM]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_FEATURE_ITEM]) {
            this.router.navigate(['dashboard']);
        }
        this.listFeature();
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('feature-items', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
            });
        }
    }
    /*
    * get api list feature
    * */
    listFeature() {
        this.app.get('features', {active: this.app.constant.ACTIVE_TRUE}).subscribe((res: any) => {
            this.listFeatures = this.app.arrToList(res.data, 'id', 'name');
        });
    }

    save() {
        this.url = 'feature-items/' + this.route.snapshot.params['id'];
        this.fd.form.value.active = this.fd.form.value.active ? 1 : 0;
        if (this.route.snapshot.params['id']) {
            this.app.post(this.url, this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Feature items has been saved');
                return this.router.navigate(['/feature-items']);
            });
        } else {
            this.app.post('feature-items', this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Feature items  has been saved');
                return this.router.navigate(['/feature-items']);
            });
        }
    }

}
