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
    public listIsBasedUomTrue: any = [];
    public data = {
        id: '',
        name: '',
        code: '',
        display_name: '',
        description: '',
        conversion_rate: '',
        isrounded: 0,
        round_priority: '',
        is_based_uom: 0,
        based_uom_id: null,
        active: 0
    };

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router
    ) {
    }

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_UOM]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_UOM]) {
            this.router.navigate(['dashboard']);
        }
        this.isBasedUom();
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('uoms', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
                this.data.is_based_uom = res.data[0].is_based_uom;
            });
        }
    }

    /*
    * get is based uom = true about table uom
    *
    * */
    isBasedUom() {
        if (this.route.snapshot.params['id']) {
            this.app.post('uoms/is-based-uom', {is_based_uom: 1, id: this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.listIsBasedUomTrue = this.app.arrToList(res.data, 'id', 'name');
            });
        } else {
            this.app.post('uoms/is-based-uom', {is_based_uom: 1}).subscribe((res: any) => {
                this.listIsBasedUomTrue = this.app.arrToList(res.data, 'id', 'name');
            });
        }
    }

    changeCheckbox(e) {
        this.data.is_based_uom = e.target.checked ? 1 : 0;
    }

    save() {
        if (this.fd.form.value.is_based_uom) {
            delete this.fd.form.value.based_uom_id;
        }
        this.url = 'uoms/' + this.route.snapshot.params['id'];
        if (this.route.snapshot.params['id']) {
            this.app.post(this.url, this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Uoms has been saved');
                return this.router.navigate(['/uom']);
            });
        } else {
            this.app.post('uoms', this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Uom has been saved');
                return this.router.navigate(['/uom']);
            });
        }
    }


}
