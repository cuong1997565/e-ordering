import {Component, Input, OnInit, forwardRef, ViewChild, ElementRef, NgZone} from '@angular/core';
import { NG_VALUE_ACCESSOR } from "@angular/forms";
import { AppService } from "../../app.service";
declare var $: any;
import * as _ from 'lodash';

@Component({
    selector: 'ele-media-selector',
    templateUrl: './media-selector.component.html',
    styleUrls: ['./media-selector.component.css'],
    providers: [
        {
            provide: NG_VALUE_ACCESSOR,
            useExisting: forwardRef(() => MediaSelectorComponent),
            multi: true
        }
    ]
})

export class MediaSelectorComponent implements OnInit {

    @ViewChild('tbMedia') tbMedia: ElementRef;
    @ViewChild('tmpMedia') tmpMedia: ElementRef;

    @Input() public id:string = 'fieldID';
    @Input() public buttonText:string = 'Select file';
    @Input() public type:number = 1;
    @Input() public relativeUrl:number = 1;
    @Input() public multiple:number = 0;

    mediaUrl: string;
    mediaList: string[] = [];

    constructor(public app: AppService,private zone: NgZone) {}
    ngOnInit()
    {
        // Build url
        this.mediaUrl = this.app.constant.BASE_WEB + 'media/dialog.php';
        this.mediaUrl += `?type=${this.type}&field_id=tmp-${this.id}&relative_url=${this.relativeUrl}&multiple=${this.multiple}&akey=${this.app.authToken}`;
    }

    ngAfterViewInit() {
        let self = this;
        $(`#media-selector-${this.id} .media-btn-add`).fancybox({
            'type'		: 'iframe',
            'autoScale'    	: false,
            'afterClose': function() { self.addMedia() },
            'closeClick': true
        });
    }

    addMedia()
    {
        if(this.tmpMedia.nativeElement.value)
        {
            try
            {
                let tmp = JSON.parse(this.tmpMedia.nativeElement.value);
                this.mediaList = _.concat(this.mediaList,tmp);
            }
            catch(e)
            {
                this.mediaList = _.concat(this.mediaList,this.tmpMedia.nativeElement.value);
            }

            this.mediaList = _.uniq(this.mediaList); // Remove duplicate item

            this.tmpMedia.nativeElement.value = ''; // empty the tmp
            this.tbMedia.nativeElement.value = JSON.stringify(this.mediaList);

            this.updateChanges();
        }
    }

    delMedia(item)
    {
        _.remove(this.mediaList, function(n) {return n == item;});

        if(this.mediaList.length == 0)
        {
            this.tbMedia.nativeElement.value = '';
        }
        else
        {
            this.tbMedia.nativeElement.value = JSON.stringify(this.mediaList);
        }

        this.updateChanges();
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
        this._value = this.tbMedia.nativeElement.value;

        try {this.mediaList = JSON.parse(this.tbMedia.nativeElement.value);} catch(e) {}
        this.zone.run(() => {}); // Hack to detect the "change" after javascript
        this.onChange(this._value);
    }

    writeValue(value: string): void {
        this._value = value;
        this.tbMedia.nativeElement.value = value;
        this.updateChanges();
    }
    /* ---------- ControlValueAccessor Area } ---------- */

}
