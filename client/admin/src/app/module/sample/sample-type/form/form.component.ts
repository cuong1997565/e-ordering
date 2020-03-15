import { Component, OnInit } from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {AppService} from "../../../../share/app.service";
import {FormData} from "../../../../share/form-data";

@Component({
    selector: 'app-sample-type-form',
    templateUrl: './form.component.html',
    styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

    constructor
    (   public app: AppService,
        private route: ActivatedRoute,
        private router: Router
    ) {}

    public fd;
    private data = {
        id: '',
        name:'',
        active: 1,
    };

    ngOnInit() {
        this.fd = new FormData(this.data);

        if(this.route.snapshot.params['id'])
        {
            this.fd.isNew = false;
            this.app.get('sample_types/form', {'id':this.route.snapshot.params['id']}).subscribe((res:any) => {
                this.fd.setData(res.data);
            });
        }
    }

    save() {
        this.app.post('sample_types/form', this.fd.form.value).subscribe((data:any) =>
        {
            this.app.flashSuccess('User has been saved');
            this.router.navigate(['/sample-type']);
        });
    }
}
