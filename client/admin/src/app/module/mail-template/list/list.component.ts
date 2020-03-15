import { Component, NgZone, OnInit} from '@angular/core';
import { AppService} from "../../../share/app.service";
import { FormData } from "../../../share/form-data";
import { ActivatedRoute, Router } from "@angular/router";

declare var $: any;

@Component({
    selector: 'app-mail-template-form',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {

    constructor
    (
        public app: AppService,
        private route: ActivatedRoute,
        private router: Router,
        private zone: NgZone
    ) { }

    public fd;
    public templates:any;
    public variable:any = [];
    private data = {
        id: '',
        subject: '',
        content: '',
    };

    ngOnInit() {
        this.getListMailTemplate();
        this.fd = new FormData(this.data);
        this.getTemplate(this.route.snapshot.params['id']);
    }

    getTemplate(id = '') {
        this.app.get('mail-template/form', {'id':id}).subscribe((res:any) => {

            if(res.data)
            {
                this.fd.setData(res.data);

                if(res.data.variable && this.app.isJsonString(res.data.variable)) {
                    let data = JSON.parse(res.data.variable);
                    this.variable = [];
                    Object.keys(data).forEach((k) => {
                        this.variable.push({key: "{{$"+k+"}}", value: data[k]})
                    });
                }

                this.zone.run(() => {}); // Hack to detect the "change" after javascript
            }
        });
    }

    getListMailTemplate() {
        this.app.get('mail-template').subscribe((res:any) => {
            this.templates = res.data;
        });
    }

    save() {
        this.app.post('mail-template/form', this.fd.form.value).subscribe((res:any) =>
        {
            this.app.flashSuccess('Data has been saved', true);
        });
    }

    change() {
        this.router.navigate(['/mail-template',this.fd.form.value['id']]);
        this.getTemplate(this.fd.form.value['id']);
    }

}
