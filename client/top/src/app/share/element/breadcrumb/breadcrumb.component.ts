import {Component, Input, OnInit} from '@angular/core';

@Component({
    selector: 'ele-breadcrumb',
    templateUrl: './breadcrumb.component.html'
})
export class BreadcrumbComponent implements OnInit {

    @Input() public icon: string;
    @Input() public items: Array<string>;

    constructor() { }

    ngOnInit() {
    }

}
