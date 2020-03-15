import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {AppService} from '../../app.service';
import {forkJoin} from 'rxjs';

@Component({
    selector: 'ele-area',
    templateUrl: './area.component.html',
    styles: [`
        .select2-element {
            position: relative;
            margin-bottom: 20px;
        }

        small.help-block {
            position: absolute !important;
            top: 30px !important;
        }
    `]
})

export class AreaSelectorComponent implements OnInit {
    @Input() fd;
    @Input() province = false;
    @Input() district = false;
    @Input() district1 = false;
    @Input() commune = false;
    @Input() village = false;
    @Input() label = 'Area';
    @Input() detail = false;
    @Input() required = false;

    public selectedProvice = new EventEmitter();
    public selectedDistrict = new EventEmitter();
    public selectedCommune = new EventEmitter();
    public selectedVillage = new EventEmitter();

    public provinces;
    public districts;
    public communes;
    public villages;

    constructor(public app: AppService) {
    }

    ngOnInit() {
        this.getInfo();
    }

    getInfo() {
        if (this.detail) {
            forkJoin(
                [
                    this.app.get('areas')
                ]).subscribe(([area]) => {
                this.provinces = this.app.arrToList(area['data'], 'id', 'name');
                this.districts = this.app.arrToList(area['data'], 'id', 'name');
                this.communes = this.app.arrToList(area['data'], 'id', 'name');
                this.villages = this.app.arrToList(area['data'], 'id', 'name');
            });
        }
    }

    provinceSelected(event) {
        this.selectedProvice.emit(event);
    }

    districtSelected(event) {
        this.selectedDistrict.emit(event);
    }

    communeSelected(event) {
        this.selectedCommune.emit(event);
    }

    villageSelected(event) {
        this.selectedVillage.emit(event);
    }

}
