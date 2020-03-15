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

    public fd;
    public url;
    private data = {
        id: '',
        name: '',
        code: '',
        active: 0
    };

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router
    ) {
    }

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_GRADE_GROUP]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_GRADE_GROUP]) {
            this.router.navigate(['dashboard']);
        }
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('grade-groups', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
            });
        }
    }

    save() {
        this.url = 'grade-group/' + this.route.snapshot.params['id'];
        this.fd.form.value.active = this.fd.form.value.active ? 1 : 0;
        if (this.route.snapshot.params['id']) {
            this.app.post(this.url, this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Grade group has been saved');
                return this.router.navigate(['/grade-group']);
            });
        } else {
            this.app.post('grade-group', this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Grade group has been saved');
                return this.router.navigate(['/grade-group']);
            });
        }
    }
}
