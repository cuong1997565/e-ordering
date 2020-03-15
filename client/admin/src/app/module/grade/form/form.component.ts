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
    public listGradeGroup: any = [];

    private data = {
        id: '',
        name: '',
        code: '',
        grade_group_id: null,
        display_name: '',
        active: 0
    };

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router
    ) { }

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_GRADE]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_GRADE]) {
            this.router.navigate(['dashboard']);
        }
        this.gradeGroup();
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('grades', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
            });
        }
    }

    /*
     * get api list grade group
     * */
    gradeGroup() {
        this.app.get('grade-groups', {active: this.app.constant.ACTIVE_TRUE}).subscribe((res: any) => {
            this.listGradeGroup = this.app.arrToList(res.data, 'id', 'name');
        });
    }

    save() {
        this.url = 'grade/' + this.route.snapshot.params['id'];
        this.fd.form.value.active = this.fd.form.value.active ? 1 : 0;
        if (this.route.snapshot.params['id']) {
            this.app.post(this.url, this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Grade has been saved');
                return this.router.navigate(['/grade']);
            });
        } else {
            this.app.post('grade', this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Grade has been saved');
                return this.router.navigate(['/grade']);
            });
        }
    }

}
