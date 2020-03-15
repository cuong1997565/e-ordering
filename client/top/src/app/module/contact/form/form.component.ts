import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {FormData} from '../../../share/form-data';
import {DomSanitizer} from '@angular/platform-browser';
import {TranslationPipe} from '../../../share/translation.pipe';

declare var $;

@Component({
    selector: 'app-contact-form',
    templateUrl: './form.component.html',
    styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

    public msg: any;

    constructor(
        public app: AppService,
        public sanitizer: DomSanitizer
    ) {
        setTimeout(() => {
            this.msg = (this.app.staticContent.leftTextContact).replace(/\r?\n/g, '<br />');
            this.msg = this.sanitizer.bypassSecurityTrustHtml(this.msg);
        }, 200);
    }

    public fd;
    private data = {
        name: '',
        phone_number: '',
        email: '',
        content: '',
    };

    ngOnInit() {
        this.fd = new FormData(this.data);
    }

    save() {
        this.app.post('contacts/form', this.fd.form.value).subscribe((data: any) => {
            alert(new TranslationPipe().transform('Contact has been sent'));
            location.reload();
        });
    }
}
