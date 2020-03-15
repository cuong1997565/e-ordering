import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {AppService} from '../../app.service';

@Component({
    selector: 'ele-active',
    templateUrl: './active.component.html'
})

export class ActiveComponent implements OnInit {
    @Input() model: string;
    @Input() id: string;

    @Input() title: string;
    @Output() modelChange = new EventEmitter();
    @Input() active: any;

    public widgetID;
    public value;

    constructor(public app: AppService) {
    }

    ngOnInit() {

        if (this.model && this.id) {
            this.value = (this.active == 1) ? true : false;
            this.widgetID = 'ele-active-' + this.id;
        } else {
            this.title = 'Invalid';
            console.log('Hey dev! Missing parameters');
        }
    }

    onClick() {
        //this.modelChange.emit(this.value);

        let data = {
            model: this.model,
            id: this.id
        };

        this.app.post('commons/active', data).subscribe(data => {
        });
    }
}
