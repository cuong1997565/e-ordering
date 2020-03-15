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
    public ld;
    public ulr;
    private data = {
        id: '',
        name: '',
        code: '',
        active: 0,
    };

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private router: Router) {
    }

    ngOnInit() {
        this.fd = new FormData(this.data);

        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('brands', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data[0]);
            });
        }
    }

    save() {
        this.ulr = 'brands/' + this.route.snapshot.params['id'];
        if (this.route.snapshot.params['id']) {
            this.app.post(this.ulr, this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Brand has been saved');
                return this.router.navigate(['/brand']);
            });
        } else {
            this.app.post('brands', this.fd.form.value).subscribe((data: any) => {
                this.app.flashSuccess('Brand has been saved');
                return this.router.navigate(['/brand']);
            });
        }
    }
}
