import { Component, Input, OnInit, forwardRef, ViewChild, ElementRef } from '@angular/core';
import { NG_VALUE_ACCESSOR } from "@angular/forms";
import { AppService } from "../../app.service";
import { langList} from "../../../../../../share/translation/config";

declare var $: any;
declare var CKEDITOR: any;

@Component({
    selector: 'ele-editor',
    templateUrl: './editor.component.html',
    styleUrls: ['./editor.component.css'],
    providers: [
        {
            provide: NG_VALUE_ACCESSOR,
            useExisting: forwardRef(() => EditorComponent),
            multi: true
        }
    ]
})

export class EditorComponent implements OnInit {

    @ViewChild('tbEditor') tbEditor: ElementRef;

    @Input() public id:string = 'fieldID';
    @Input() public multipleLang:boolean = false;
    @Input() public require:boolean = false;

    public languages;
    public currentLanguage = 'vn';
    public data = {};

    constructor(
        public app: AppService
    ) {}

    ngOnInit()
    {
        this.languages = langList;
        this.data[this.id] = {};
    }

    ngAfterViewInit()
    {
        if (this.multipleLang) {
            if (this._value) {
                this.data[this.id] = this.saveParseJson(this._value);
            }
            for(let language of this.languages) {
                this.initCkEditor(this.id, language.language)
            }
        } else {
            if (this._value) {
                this.data[this.id] = this._value;
            }
            this.initCkEditor(this.id);
        }
    }

    /* ---------- ValueAccessor Area { ---------- */
    // https://angular.io/api/forms/ControlValueAccessor

    onChange: (_: any) => void = (_: any) => {};
    onTouched: () => void = () => {};

    registerOnChange(fn: any): void {this.onChange = fn;}
    registerOnTouched(fn: any): void {this.onTouched = fn;}

    private _value:string;

    writeValue(value: string): void
    {
        if(value.length)
        {
            this.setValueCkEditor(value);
            this._value = value;
        }
    }

    public ngOnDestroy() {

        // If some error happen, try to uncomment this
        //if (CKEDITOR.instances[this.id]) CKEDITOR.instances[this.id].destroy();
    }

    /* ---------- ControlValueAccessor Area } ---------- */



    showInput(language) {
        this.currentLanguage = language;
    }

    initCkEditor(originId, language:string = '') {
        let id = originId;
        if (language) {
            id = originId+'_'+language;
        }
        CKEDITOR.replace(id,
        {
            filebrowserBrowseUrl : this.app.constant.BASE_WEB + 'media/dialog.php?type=2&editor=ckeditor&fldr=&multiple=0&akey='+this.app.authToken,
            contentsCss : ['/assets/css/ckeditor.css'],
            //filebrowserUploadUrl : this.app.constant.BASE_WEB + 'media/dialog.php?type=2&editor=ckeditor&fldr=&multiple=0&akey='+this.app.authToken,
            //filebrowserImageBrowseUrl : this.app.constant.BASE_WEB + 'media/dialog.php?type=1&editor=ckeditor&fldr=&multiple=0&akey='+this.app.authToken
        });

        let self = this;
        CKEDITOR.instances[id].on('change', function() {
            let value = CKEDITOR.instances[id].getData();
            if (language) {
                if (!value.length) {
                    delete self.data[originId][language];
                } else {
                    self.data[originId][language] = CKEDITOR.instances[id].getData();
                    value = self.data[originId];
                }
            }
            if (self.multipleLang) {
				if(self.require){
					let valueMultipleLang = Object.keys(self.data[originId]).length == langList.length ? JSON.stringify(value) : '';
					self.onChange(valueMultipleLang);
				} else {
					self.onChange(JSON.stringify(value));
				}
            } else {
                self.onChange(value);
            }
        });

        CKEDITOR.instances[id].on("instanceReady", function(event)
        {
            self.setValueCkEditor(self._value);
        });
    }

    setValueCkEditor(value) {
        if (!value) {
            return;
        }
        if (this.multipleLang) {
            let dataValue = this.saveParseJson(value);
            if (typeof dataValue == 'object') {
                this.data[this.id] = dataValue;
            }
            for (let item of this.languages) {
                if (typeof CKEDITOR.instances[this.id +'_'+item.language] != 'undefined') {
                    CKEDITOR.instances[this.id +'_'+item.language].setData(dataValue[item.language]);
                }

            }
        } else {

            if (typeof CKEDITOR.instances[this.id] != 'undefined') {
                CKEDITOR.instances[this.id].setData(value);
            }
        }
    }

    saveParseJson(text) {
        if (this.app.isJsonString(text)) {
            return JSON.parse(text);
        }
        let result = {};
        result[this.currentLanguage] = text;
        return result;
    }

}
