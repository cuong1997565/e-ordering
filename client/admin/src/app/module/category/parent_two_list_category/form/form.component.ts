import { Component, OnInit } from '@angular/core';
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
        name: '',
        code: '',
        active: 0
    };
    public category_two = {
        id: '',
        name: '',
        parent_id: '',
    };
    public category = {
        id: '',
        name: '',
    };

    public category_parent_id;
    public url;
    public code;

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_CATEGORY]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_CATEGORY]) {
            this.router.navigate(['dashboard']);
        }
        this.category_parent_id = JSON.parse(localStorage.getItem('category_parent_id'));
        if (this.route.snapshot.params['category_two']) {
            this.app.get('categories/', {'id': this.route.snapshot.params['category_two']}).subscribe((res: any) => {
                this.category_two = res.data[0];
            });
        }
        if (this.category_parent_id) {
            this.app.get('categories/', {'id': this.category_parent_id}).subscribe((res: any) => {
                this.category = res.data[0];
            });
        }

        this.route.params.subscribe((e) => {
            this.code = e.category_two;
        });

        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('categories', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
                this.loaded = true;
            }, (err) => {
                this.router.navigate(['/parent_two_list_category', this.category_two.id]);
            });
        } else {
            this.loaded = true;
        }
    }

    save() {
        this.url = 'categories/' + this.route.snapshot.params['id'];
        this.fd.form.value['level'] = 3;
        this.fd.form.value['parent_id'] = this.code;
        this.fd.form.value.code = $.trim(this.fd.form.value.code);
        this.fd.form.value.active = this.fd.form.value.active ? 1 : 0;
        if (this.route.snapshot.params['id']) {
            this.app.post(this.url, this.fd.form.value).subscribe((res: any) => {
                this.app.flashSuccess('Data has been saved');
                this.router.navigate(['/parent_two_list_category', this.category_two.id]);
            }, (err) => {
                $('.select2-element small').css({'position': 'absolute', 'top': '30px'});
            });
        } else {
            this.app.post('categories', this.fd.form.value).subscribe((res: any) => {
                this.app.flashSuccess('Data has been saved');
                this.router.navigate(['/parent_two_list_category', this.category_two.id]);
            }, (err) => {
                $('.select2-element small').css({'position': 'absolute', 'top': '30px'});
            });
        }
    }

}
