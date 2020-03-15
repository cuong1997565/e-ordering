import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {AppService} from '../../../app.service';
import {TranslationPipe} from '../../../translation.pipe';

declare var $: any;

@Component({
    selector: 'ele-village-selector',
    templateUrl: './village-selector.component.html'
})

export class VillageSelectorComponent implements OnInit {
    @Input() identity_name;
    @Input() fd;
    @Input() value = '';
    @Input() communeEvent;
    @Output() villageEvent = new EventEmitter();
    public villages;

    constructor(public app: AppService) {
    }

    ngOnInit() {

    }

    initSelect() {
        let that = this;
        $('#' + this.identity_name).select2({
            placeholder: new TranslationPipe().transform('select_village'),
        }).on('select2:select', function (e) {
            let tmp = [];
            tmp[that.identity_name] = e.params.data.id;
            that.fd.setData(tmp);
            that.villageEvent.emit({id: e.params.data.id, needReset: true});
        });
    }

    ngAfterViewInit() {
        this.initSelect();

        this.communeEvent.subscribe((communeData) => {
            if (communeData.needReset) {
                this.getVillageReset(communeData.id);
            } else {
                this.getVillage(communeData.id);
            }
        });
    }

    getVillageReset(commune_id = null) {
        this.app.get('village', {'commune_id': commune_id}).subscribe((res: any) => {
            this.villages = this.convertData(res.data);

            $('#' + this.identity_name).html('').select2('destroy').select2({
                placeholder: new TranslationPipe().transform('select_village'),
                data: this.villages
            });

            let tmp = [];
            tmp[this.identity_name] = null;
            this.fd.setData(tmp);

            $('#' + this.identity_name).val('').trigger('change');
            this.villageEvent.emit({id: this.value, needReset: true});
        });
    }

    getVillage(commune_id = null, reset = false) {
        this.app.get('village', {'commune_id': commune_id}).subscribe((res: any) => {
            this.villages = this.convertData(res.data);

            $('#' + this.identity_name).html('').select2('destroy').select2({
                placeholder: new TranslationPipe().transform('select_village'),
                data: this.villages
            });

            if (reset) {
                let tmp = [];
                tmp[this.identity_name] = null;
                this.fd.setData(tmp);
                this.villageEvent.emit({id: '', reset: true}); // sent reset option
            }

            if (this.value != '' || this.value != null || typeof this.value != 'string') {
                $('#' + this.identity_name).val(this.value).trigger('change');
                this.villageEvent.emit({id: this.value, needReset: false});
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
