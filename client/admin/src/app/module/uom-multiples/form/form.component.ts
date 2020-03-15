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
    public listUoms: any = [];
    public url;
    private data = {
        id: '',
        uom_id: null,
        name: '',
        code: '',
        display_name: '',
        description: '',
        conversion_rate: '',
        isrounded: 0,
        round_priority: ''
    };

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router
    ) {
    }

    ngOnInit() {
        this.fd = new FormData(this.data);
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_UOM_MULTIPLE]) {
            this.router.navigate(['dashboard']);
        }
        if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_UOM_MULTIPLE]) {
            this.router.navigate(['dashboard']);
        }
        this.listUom();
        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('uom-multiples', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
            });
        }
    }

    listUom() {
        this.app.get('uoms').subscribe((res: any) => {
            this.listUoms = this.app.arrToList(res.data, 'id', 'name');
        });
    }

    save() {
        this.url = 'uom-multiples/' + this.route.snapshot.params['id'];
        this.fd.form.value.code = $.trim(this.fd.form.value.code);

        if (this.route.snapshot.params['id']) {
            this.app.post(this.url, this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Uom multiple  has been saved');
                return this.router.navigate(['/uom-multiple']);
            });
        } else {
            this.app.post('uom-multiples', this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Uom multiple has been saved');
                return this.router.navigate(['/uom-multiple']);
            });
        }
    }

}
