import {Component, Input, OnInit, forwardRef, ViewChild, ElementRef } from '@angular/core';
import { NG_VALUE_ACCESSOR } from "@angular/forms";
import { AppService } from "../../app.service";
declare var $: any;

@Component({
    selector: 'ele-custom-input',
    templateUrl: './custom-input.component.html',
    styleUrls: ['./custom-input.component.css'],
    providers: [
        {
            provide: NG_VALUE_ACCESSOR,
            useExisting: forwardRef(() => CustomInputComponent),
            multi: true
        }
    ]
})

export class CustomInputComponent implements OnInit {

    @ViewChild('tbCustomInput') tbCustomInput: ElementRef;

    @Input() public id:string = 'fieldID';

    constructor(
        public app: AppService
    ) {}

    ngOnInit()
    {


    }

    ngAfterViewInit() {

    }


    /* ---------- ValueAccessor Area { ---------- */
    // https://angular.io/api/forms/ControlValueAccessor

    onChange: (_: any) => void = (_: any) => {};
    onTouched: () => void = () => {};

    registerOnChange(fn: any): void {this.onChange = fn;}
    registerOnTouched(fn: any): void {this.onTouched = fn;}

    writeValue(value: string): void {
        this.tbCustomInput.nativeElement.value = value;
    }

    /* ---------- ControlValueAccessor Area } ---------- */

    update()
    {
        this.onChange(this.tbCustomInput.nativeElement.value);
    }

}
