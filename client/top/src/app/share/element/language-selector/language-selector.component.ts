import {Component, OnInit} from '@angular/core';
import {AppService} from '../../app.service';
import {DEFAULT_LANG, DEFAULT_LANG_TOP, langListTop} from '../../../../../../share/translation/config';

@Component({
    selector: 'ele-language-selector',
    templateUrl: './language-selector.component.html',
    styleUrls: [
        './language-selector.component.css'
    ]
})
export class LanguageSelectorComponent implements OnInit {

    public langList = langListTop;

    public curLangObj: any;
    public showLang = false;

    constructor(public app: AppService) {
    }

    ngOnInit() {
        this.app.curLang = this.app.getConfig('LANG_TOP', DEFAULT_LANG_TOP);
        this.curLangObj = this.langList.find((item) => {
            return item.key === this.app.curLang;
        });
    }

    setLanguage(item) {
        this.showLang = false;
        this.curLangObj = item;
        this.app.setConfig('LANG_TOP', item.key);
        window.location.reload();
    }

    showSelect() {
        this.showLang = true;
    }
}
