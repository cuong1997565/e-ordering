import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {DEFAULT_LANG} from '../../../../../../share/translation/config';

@Component({
    selector: 'app-translation-dev',
    templateUrl: './dev.component.html',
    styleUrls: ['./dev.component.css']
})

export class DevComponent implements OnInit {

    constructor(public app: AppService, private route: ActivatedRoute, private router: Router) {
    }

    public list: any = [];
    public currentLang: any;
    public data: any = [];
    public langs: any;

    ngOnInit() {
        this.langs = window['listLang'];
        let listLang = window['listLang'];
        if (!this.route.snapshot.params['lang'] || Object.values(listLang).indexOf(this.route.snapshot.params['lang']) < 0) {
            let lang = this.app.getConfig('LANG', DEFAULT_LANG);

            this.router.navigate(['/translation', lang]);
        }

        this.currentLang = this.route.snapshot.params['lang'];
        this.getData();
    }

    getData() {
        this.app.get('translations/dev', {lang: this.currentLang}).subscribe((res: any) => {
            this.list = res.data;
        });
    }

    changeLang(lang) {
        this.router.navigate(['/translation/dev', lang]);
        this.currentLang = lang;
        this.getData();
    }

    action(item) {
        if (this.data.includes(item.id)) {
            this.data.splice(this.data.indexOf(item.id), 1);
        } else {
            this.data.push(item.id);
        }
    }

    checkAll() {
        if (this.data.length == this.list.length) {
            this.data = [];
        } else {
            this.data = [];
            this.list.forEach((item) => {
                this.data.push(item.id);
            });
        }
    }

    gen() {
        this.app.get('translations/gen').subscribe((res: any) => {
            this.router.navigate(['/translation']);
        });
    }

    addToServer() {
        this.app.post('translations/dev', {'data': this.data, 'lang': this.currentLang}).subscribe((res: any) => {
            this.app.flashSuccess('Data has been saved', true);
            this.getData();
        });
    }
}
