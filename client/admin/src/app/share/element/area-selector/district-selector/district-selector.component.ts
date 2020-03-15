import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {AppService} from "../../../app.service";
import {TranslationPipe} from "../../../translation.pipe";

declare var $:any;

@Component({
    selector: 'ele-district-selector',
    templateUrl: './district-selector.component.html'
})

export class DistrictSelectorComponent implements OnInit
{
    @Input() identity_name;
    @Input() fd;
    @Input() value = '';
    @Input() provinceEvent;
    @Output() districtEvent = new EventEmitter();
    public districts;

    constructor(public app: AppService) { }

    ngOnInit() {

    }

    initSelect() {
        let that = this;
        $('#' + this.identity_name).select2({
            placeholder: new TranslationPipe().transform("select_district")
        }).on('select2:select', function(e) {
            let tmp = [];
            tmp[that.identity_name] = e.params.data.id;
            that.fd.setData(tmp);
            that.districtEvent.emit({id: e.params.data.id, needReset: true});
        });
    }

    ngAfterViewInit(){
        this.initSelect();

        this.provinceEvent.subscribe((provinceData) => {
            if (provinceData.needReset) {
                this.getDistrictReset(provinceData.id);
            } else {
                this.getDistrict(provinceData.id);
            }
        });
    }

    getDistrictReset(province_id = null, reset = false) {
        this.app.get('districts', {'province_id': province_id}).subscribe((res: any) => {
            this.districts = this.convertData(res.data);

            $('#' + this.identity_name).html('').select2('destroy').select2({
                placeholder: new TranslationPipe().transform("select_district"),
                data: this.districts
            });

            let tmp = [];
            tmp[this.identity_name] = null;
            this.fd.setData(tmp);

            $('#' + this.identity_name).val('').trigger('change');
            this.districtEvent.emit({id: this.value, needReset: true});
        });
    }

    getDistrict(province_id = null, reset = false) {
        this.app.get('districts', {'province_id': province_id}).subscribe((res:any) => {
            this.districts = this.convertData(res.data);

            $('#' + this.identity_name).html('').select2('destroy').select2({
                placeholder: new TranslationPipe().transform("select_district"),
                data: this.districts
            });

            if (this.value != '' || typeof this.value != 'string') {
                $('#' + this.identity_name).val(this.value).trigger('change');
                this.districtEvent.emit({id: this.value, needReset: false});
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
