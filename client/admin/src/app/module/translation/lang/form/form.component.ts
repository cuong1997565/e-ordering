import { Component, OnInit } from '@angular/core';
import { AppService} from "../../../../share/app.service";
import { ActivatedRoute, Router} from "@angular/router";
import { FormData } from "../../../../share/form-data";
import { UploadService } from "../../../../share/element/upload/upload.service";

@Component({
    selector: 'app-lang-form',
    templateUrl: './form.component.html',
    styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

    constructor
    (
        public app: AppService,
        private route: ActivatedRoute,
        private router: Router,
    ) { }

    public listSampleType;

    public fd;
    private data = {
        id: '',
        lang: ''
    };

    ngOnInit() {
        this.fd = new FormData(this.data);

        if(this.route.snapshot.params['id'])
        {
            this.fd.isNew = false;
            this.app.get('langs/form', {'id':this.route.snapshot.params['id']}).subscribe((res:any) => {
                this.fd.setData(res.data);
            });
        }
    }

    save() {

        this.app.post('langs/form', this.fd.form.value).subscribe((res:any) =>
        {
            this.app.flashSuccess('Data has been saved');
            this.router.navigate(['/translation/lang']);
        });
    }
}
