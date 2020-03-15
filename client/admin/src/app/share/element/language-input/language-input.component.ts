import { Component, Input, OnInit, forwardRef, ViewChild, ElementRef, ChangeDetectorRef} from '@angular/core';
import { NG_VALUE_ACCESSOR } from "@angular/forms";
import { AppService } from "../../app.service";
import { constant } from "../../../config/base";
import { langList } from "../../../../../../share/translation/config";

declare var $: any;

@Component({
    selector: 'ele-language-input',
    templateUrl: './language-input.component.html',
    styleUrls: ['./language-input.component.css'],
    providers: [
        {
            provide: NG_VALUE_ACCESSOR,
            useExisting: forwardRef(() => LanguageInputComponent),
            multi: true
        }
    ]
})

export class LanguageInputComponent implements OnInit {

    @ViewChild('tbLangInput') tbLangInput: ElementRef;

    @Input() public id:string = 'fieldID';
    @Input() public type:string = 'text';
    @Input() public placeholder:string = '';
    @Input() public require:boolean = false;

    public languages = [];
    public currentLanguage = 'vn';
    public data = {};
    public constant = constant;

    constructor(
        public app: AppService,
        private cdRef:ChangeDetectorRef
    ) {}
    ngOnInit()
    {
        this.languages = langList;
        /*if (!this.placeholder) {
            this.placeholder = this.id;
        }*/
        this.data[this.id] = {};
    }

    ngAfterViewInit() {
        if (this._value) {
            this.data[this.id] = this.saveParseJson(this._value);
        }
        this.cdRef.detectChanges()
    }


    /* ---------- ValueAccessor Area { ---------- */
    // https://angular.io/api/forms/ControlValueAccessor

    _value: string = '';
    onChange: (_: any) => void = (_: any) => {};
    onTouched: () => void = () => {};

    registerOnChange(fn: any): void {this.onChange = fn;}
    registerOnTouched(fn: any): void {this.onTouched = fn;}

    updateChanges()
    {
        this._value = this.tbLangInput.nativeElement.value;
        this.onChange(this._value);
    }

    writeValue(value: string): void {
        this._value = value;
        this.tbLangInput.nativeElement.value = value;
        if (value) {
            this.data[this.id] = this.saveParseJson(value);
        }
        this.updateChanges();
    }

    showInput(language) {
        this.currentLanguage = language;
        $('.input_'+this.id+'_'+language).focus();
    }

    onKeyUp(event) {
        if (!event.target.value) {
            delete this.data[this.id][this.currentLanguage];
            this.writeValue('');
        } else {
            this.data[this.id][this.currentLanguage] = event.target.value;
			if (this.require){
				if (Object.keys(this.data[this.id]).length === this.languages.length) {
					this.writeValue(JSON.stringify(this.data[this.id]))
				} else {
					this.writeValue('');
				}
			} else {
				this.writeValue(JSON.stringify(this.data[this.id]))
			}
        }
    }
    /* ---------- ControlValueAccessor Area } ---------- */


    saveParseJson(text) {
        if (this.app.isJsonString(text)) {
            return JSON.parse(text);
        }
        let result = {};
        result[this.currentLanguage] = text;
        return result;
    }

}
