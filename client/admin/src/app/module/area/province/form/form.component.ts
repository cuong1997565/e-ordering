import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../../share/form-data';
import {UploadService} from '../../../../share/element/upload/upload.service';

declare var $: any;

@Component({
    selector: 'app-city-form',
    templateUrl: './form.component.html',
    styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

    constructor
    (
        public app: AppService,
        private route: ActivatedRoute,
        private router: Router,
    ) {
    }

    public fd;
    public ulr;
    private data = {
        id: '',
        code: '',
        name: '',
        parent_id: 0,
    };

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_AREA]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_AREA]) {
            this.router.navigate(['dashboard']);
        }
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('areas', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
            });
        }
    }

    save() {
        this.ulr = 'areas/' + this.route.snapshot.params['id'];
        this.fd.form.value['level'] = 1;
        if (this.route.snapshot.params['id']) {
            this.app.post(this.ulr, this.fd.form.value).subscribe((res: any) => {
                this.app.flashSuccess('Data has been saved');
                this.router.navigate(['/province']);
            }, (err) => {
                $('.select2-element small').css({'position': 'absolute', 'top': '30px'});
            });

        } else {
            this.app.post('areas', this.fd.form.value).subscribe((res: any) => {
                this.app.flashSuccess('Data has been saved');
                this.router.navigate(['/province']);
            }, (err) => {
                $('.select2-element small').css({'position': 'absolute', 'top': '30px'});
            });
        }
    }
}
