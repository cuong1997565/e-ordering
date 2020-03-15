import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {AppService} from '../../../app.service';
import {TranslationPipe} from '../../../translation.pipe';
import {forkJoin} from 'rxjs';

declare var $: any;

@Component({
    selector: 'ele-province-selector',
    templateUrl: './province-selector.component.html'
})

export class ProvinceSelectorComponent implements OnInit {
    @Input() identity_name;
    @Input() fd;
    @Input() value = '';
    @Output() provinceEvent = new EventEmitter();
    public provinces;

    constructor(public app: AppService) {
    }

    ngOnInit() {

    }

    initSelect() {
        $('#' + this.identity_name).select2({
            placeholder: new TranslationPipe().transform('select_province')
        });
        let that = this;
        $('#' + this.identity_name).on('select2:select', function (e) {
            let tmp = [];
            tmp[that.identity_name] = e.params.data.id;
            that.fd.setData(tmp);
            that.provinceEvent.emit({id: e.params.data.id, needReset: true});
        });
    }

    ngAfterViewInit() {
        this.initSelect();

        this.getProvince();
    }

    getProvince() {
        return forkJoin([
            this.app.get('areas')
        ]).subscribe(([province]) => {
            this.provinces = this.convertData(province['data']);
            $('#' + this.identity_name).html('').select2('destroy').select2({
                placeholder: new TranslationPipe().transform('select_province'),
                data: this.provinces
            });

            if (this.value != '' || typeof this.value != 'string') {
                $('#' + this.identity_name).val(this.value).trigger('change');
                this.provinceEvent.emit({id: this.value, needReset: false});
            } else {
                $('#' + this.identity_name).val('').trigger('change');
            }
        });
    }

    convertData(data) {
        let res = [];
        $.each(data, (e, v) => {
            res.push({id: v.id, text: v.name});
        });
        return res;
    }
}
