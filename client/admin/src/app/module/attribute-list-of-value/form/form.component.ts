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
    public listAttributes: any = [];
    public data = {
        id: '',
        attribute_id: null,
        value: '',
        active: 0

    };

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router
    ) {
    }

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_ATTRIBUTE_LIST_OF_VALUE]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_ATTRIBUTE_LIST_OF_VALUE]) {
            this.router.navigate(['dashboard']);
        }
        this.Attributes();
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('attribute-lists-of-value', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
            });
        }
    }

    /*
    * get api attributes
    * */
    Attributes() {
        this.app.get('attributes', {
            type: this.app.constant.Attributes_Type_List, active: this.app.constant.ACTIVE_TRUE
        }).subscribe((res: any) => {
            this.listAttributes = this.app.arrToList(res.data, 'id', 'name');
        });
    }

    save() {
        this.url = 'attribute-lists-of-value/' + this.route.snapshot.params['id'];
        this.fd.form.value.active = this.fd.form.value.active ? 1 : 0;

        if (this.route.snapshot.params['id']) {
            this.app.post(this.url, this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Attribute list of value  has been saved');
                return this.router.navigate(['/attribute-list-of-value']);
            });
        } else {
            this.app.post('attribute-lists-of-value', this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Attribute list of value  has been saved');
                return this.router.navigate(['/attribute-list-of-value']);
            });
        }
    }


}
