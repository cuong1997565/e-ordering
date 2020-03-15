import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router} from "@angular/router";
import { AppService } from "../../../share/app.service";
import { FormData } from "../../../share/form-data";
import { DEFAULT_LANG } from "../../../../../../share/translation/config";

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
        private router: Router) {
    }

    public fd;
    public a = true;
    private data = {
        id: '',
        key: '',
        lang: '',
        trans: '',
        type: 1
    };
    public lang;

    ngOnInit() {
        let listLang = window['listLang'];
        this.lang = this.route.snapshot.params['lang'];
        if(!this.route.snapshot.params['lang'] || Object.values(listLang).indexOf(this.route.snapshot.params['lang']) < 0){
            let lang = this.app.getConfig('LANG', DEFAULT_LANG);

            this.router.navigate(["/translation/form",lang,this.route.snapshot.params['id'] ? this.route.snapshot.params['id'] : ''])
        }

        this.fd = new FormData(this.data);

        if(this.route.snapshot.params['id'])
        {
            this.fd.isNew = false;
            this.app.get('translations/form', {'id':this.route.snapshot.params['id']}).subscribe((res:any) => {

                this.fd.setData(res.data);
            });
        }
    }

    save() {
        this.fd.form.value['lang'] = this.route.snapshot.params['lang'];

        this.app.post('translations/form', this.fd.form.value).subscribe((res:any) =>
        {
            this.app.flashSuccess('Data has been saved');
            this.router.navigate(['/translation', this.lang]);
        });
    }

}
