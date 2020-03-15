import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {AppService} from "../../../app.service";
import {TranslationPipe} from "../../../translation.pipe";

declare var $:any;

@Component({
    selector: 'ele-commune-selector',
    templateUrl: './commune-selector.component.html'
})

export class CommuneSelectorComponent implements OnInit
{
    @Input() identity_name;
    @Input() fd;
    @Input() value = '';
    @Input() districtEvent;
    @Output() communeEvent = new EventEmitter();
    public communes;

    constructor(public app: AppService) { }

    ngOnInit() {

    }

    initSelect() {
        let that = this;
        $('#' + this.identity_name).select2({
            placeholder: new TranslationPipe().transform("select_commune")
        }).on('select2:select', function(e) {
            let tmp = [];
            tmp[that.identity_name] = e.params.data.id;
            that.fd.setData(tmp);
            that.communeEvent.emit({id: e.params.data.id, needReset: true});
        });
    }

    ngAfterViewInit(){
        this.initSelect();

        this.districtEvent.subscribe((districtData) => {
            if (districtData.needReset) {
                this.getCommuneReset(districtData.id);
            } else {
                this.getCommune(districtData.id);
            }
        });
    }

    getCommuneReset(district_id = null) {
        this.app.get('communes', {'district_id': district_id}).subscribe((res:any) => {
            this.communes = this.convertData(res.data);

            $('#' + this.identity_name).html('').select2('destroy').select2({
                placeholder: new TranslationPipe().transform("select_commune"),
                data: this.communes
            });

            let tmp = [];
            tmp[this.identity_name] = null;
            this.fd.setData(tmp);

            $('#' + this.identity_name).val('').trigger('change');
            this.communeEvent.emit({id: this.value, needReset: true});
        });
    }

    getCommune(district_id = null, reset = false) {
        this.app.get('communes', {'district_id': district_id}).subscribe((res:any) => {
            this.communes = this.convertData(res.data);

            $('#' + this.identity_name).html('').select2('destroy').select2({
                placeholder: new TranslationPipe().transform("select_commune"),
                data: this.communes
            });

            if (this.value != '' || this.value !=  null || typeof this.value != 'string') {
                $('#' + this.identity_name).val(this.value).trigger('change');
                this.communeEvent.emit({id: this.value, needReset: false});
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
