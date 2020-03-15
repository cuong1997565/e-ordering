import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../../share/form-data';

declare var $: any;

@Component({
    selector: 'app-form',
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
    public loaded = false;
    public data = {
        id: '',
        code: '',
        province_id: '',
        district_id: '',
        name: '',
        active: 0,
    };
    public district = {
        id: '',
        name: '',
        parent_id: '',
    };
    public province = {
        id: '',
        name: '',
    };
    public parent_id;
    public url;
    public code;

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_AREA]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_AREA]) {
            this.router.navigate(['dashboard']);
        }
        this.parent_id = JSON.parse(localStorage.getItem('parent_id'));
        if (this.route.snapshot.params['district']) {
            this.app.get('areas/', {'id': this.route.snapshot.params['district']}).subscribe((res: any) => {
                this.district = res.data[0];
            });
        }
        if (this.parent_id) {
            this.app.get('areas/', {'id': this.parent_id}).subscribe((res: any) => {
                this.province = res.data[0];
            });
        }

        this.route.params.subscribe((e) => {
            this.code = e.district;
        });

        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('areas', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
                this.loaded = true;
            }, (err) => {
                this.router.navigate(['/commune', this.district.id]);
            });
        } else {
            this.loaded = true;
        }
    }

    save() {
        this.url = 'areas/' + this.route.snapshot.params['id'];
        this.fd.form.value['level'] = 3;
        this.fd.form.value['parent_id'] = this.code;
        if (this.route.snapshot.params['id']) {
            this.app.post(this.url, this.fd.form.value).subscribe((res: any) => {
                this.app.flashSuccess('Data has been saved');
                this.router.navigate(['/commune', this.district.id]);
            }, (err) => {
                $('.select2-element small').css({'position': 'absolute', 'top': '30px'});
            });
        } else {
            this.app.post('areas', this.fd.form.value).subscribe((res: any) => {
                this.app.flashSuccess('Data has been saved');
                this.router.navigate(['/commune', this.district.id]);
            }, (err) => {
                $('.select2-element small').css({'position': 'absolute', 'top': '30px'});
            });
        }
    }
}
