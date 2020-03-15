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
    public listProductTypes: any = [];
    public getAttributeListsOfValue: any = [];
    public data = {
        id: '',
        name: '',
        code: '',
        product_type_id: null,
        description: '',
        type: '',
        active: 0,
        sequence: ''
    };

    public idAttribute = 0;

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router
    ) { }

    ngOnInit() {
        this.fd = new FormData(this.data);
        this.productType();
        this.AttributeListsOfValues();
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_ATTRIBUTE]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_ATTRIBUTE]) {
            this.router.navigate(['dashboard']);
        }
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('attributes', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.idAttribute = this.route.snapshot.params['id'];
                this.data.type = res.data[0].type;
                this.fd.setData(res.data[0]);
            });
        }
    }
    /*
    * get api product type
    * */
    productType() {
        this.app.get('product-types', { 'active' : this.app.constant.ACTIVE_TRUE }).subscribe((res: any) => {
            this.listProductTypes = this.app.arrToList(res.data, 'id', 'name');
        });
    }
    /*
    * change type attribute
    * */
    changeAttributeType(e) {
            this.data.type = e.target.value;
        this.fd.form.controls['attribute_label'].patchValue('');

    }

    AttributeListsOfValues() {
        this.app.get('attribute-lists-of-value' , { attribute_id : this.route.snapshot.params['id'] }).subscribe((res: any) => {
            this.getAttributeListsOfValue = res.data;
        });
    }

    save() {
        this.url = 'attributes/' + this.route.snapshot.params['id'];
        this.fd.form.value.active = this.fd.form.value.active ? 1 : 0;

        if (this.route.snapshot.params['id']) {
            this.app.post(this.url, this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Attributes  has been saved');
                return this.router.navigate(['/attributes']);
            });
        } else {
            this.app.post('attributes', this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Attributes has been saved');
                return this.router.navigate(['/attributes']);
            });
        }
    }

}
