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
    public category_three = {
        id: '',
        name: '',
    };
    public parent_id;
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
        this.parent_id = JSON.parse(localStorage.getItem('category_parent_id'));
        this.category_two = JSON.parse(localStorage.getItem('category_one_parent_id'));
        let dataQuery = {limit: 10, sort: 'name', direction: 'asc', level: 4};
        if (this.route.snapshot.params['category_three']) {
            this.app.get('categories', {'id': this.route.snapshot.params['category_three']}).subscribe((res: any) => {
                this.category_three = res.data[0];
            });
        }

        this.route.params.subscribe((e) => {
            this.code = e.category_three;
        });

        if (this.parent_id) {
            dataQuery['parent_id'] = this.parent_id;
            this.app.get('categories', {'id': this.parent_id}).subscribe((res: any) => {
                this.category = res.data[0];
            });
        }
        if (this.category_two) {
            this.app.get('categories', {'id': this.category_two}).subscribe((res: any) => {
                this.category_two = res.data[0];
            });
        }

        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('categories', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
            });
        }
    }

    save() {
        this.url = 'categories/' + this.route.snapshot.params['id'];
        this.fd.form.value['level'] = 4;
        this.fd.form.value['parent_id'] = this.code;
        this.fd.form.value.code = $.trim(this.fd.form.value.code);
        this.fd.form.value.active = this.fd.form.value.active ? 1 : 0;
        if (this.route.snapshot.params['id']) {
            this.app.post(this.url, this.fd.form.value).subscribe((res: any) => {
                this.app.flashSuccess('Data has been saved');
                this.router.navigate(['/parent_three_list_category', this.category_three.id]);
            }, (err) => {
                $('.select2-element small').css({'position': 'absolute', 'top': '30px'});
            });
        } else {
            this.app.post('categories', this.fd.form.value).subscribe((res: any) => {
                this.app.flashSuccess('Data has been saved');
                this.router.navigate(['/parent_three_list_category', this.category_three.id]);
            }, (err) => {
                $('.select2-element small').css({'position': 'absolute', 'top': '30px'});
            });
        }
    }

}
