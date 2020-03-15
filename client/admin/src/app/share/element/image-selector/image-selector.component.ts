import {Component, OnInit, Input, forwardRef} from '@angular/core';
import {constant} from "../../../config/base";
import {LanguageInputComponent} from "../language-input/language-input.component";
import {NG_VALUE_ACCESSOR} from "@angular/forms";

@Component({
    selector: 'ele-image-selector',
    templateUrl: './image-selector.component.html',
    styleUrls: ['./image-selector.component.css'],
    providers: [
        {
            provide: NG_VALUE_ACCESSOR,
            useExisting: forwardRef(() => ImageSelectorComponent),
            multi: true
        }
    ]
})
export class ImageSelectorComponent implements OnInit {

    @Input() public id:string;
    @Input() public data = [];

    public filePath;
    public fileExtension = '.png';

    constructor() {
    }

    ngOnInit() {
        this.filePath = constant.BASE_WEB+'admin/assets/img/element/image-selector/'+this.id+'/';
    }

    _value: string = '';
    onChange: (_: any) => void = (_: any) => {};
    onTouched: () => void = () => {};

    registerOnChange(fn: any): void {this.onChange = fn;}
    registerOnTouched(fn: any): void {this.onTouched = fn;}

    writeValue(value: string): void {
        this._value = value;
    }

    setValue(value) {
        this._value = value;
        this.onChange(value);
    }

}
