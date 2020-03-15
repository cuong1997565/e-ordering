import { Component, OnInit, Input } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
declare var $:any;

@Component({
  selector: 'ele-shortcode',
  templateUrl: './shortcode.component.html'
})
export class ShortcodeComponent implements OnInit {
    @Input() images:any = [{"src": "https://placeimg.com/501/302/any", "title": "image -1"},{"src": "https://placeimg.com/501/301/any", "title": "image 0"}, {"src": "https://placeimg.com/500/301/any", "title": "image 1"}, {"src": "https://placeimg.com/500/302/any", "title": "image 1"}];
    @Input() config:any = {"dots": true, "slidesToShow": 3, "focusOnSelect": true, "centerMode": true};
    @Input() configFor:any = {"slidesToShow": 1, "arrows" : true, "dots": false, "focusOnSelect": false, "draggable": false};
    @Input() styles:any = {"width": "500px", "margin": "0 auto"};
    @Input() genre:any;
    @Input() dataClass:any;
    @Input() dataThumbnail:any;
    @Input() dataShowImage:any;


    public html;

    constructor(private sanitizer: DomSanitizer) {}

    ngOnInit() {
        if (this.genre == 'slick') {
            this.html = this.slick();
        }
    }

    private slick() {
        let html;
        try {
            let s_images = this.images;
            let s_config = this.config;
            let s_class  = this.dataClass;
            let s_styles = this.styles;
            let tmpImageDiv = '';
            let tmpDOM = '.' + s_class + '-nav';
            let tmpDOMFor = '.slider-for-' + s_class;
            $.each(s_images, (k, v) => {

                tmpImageDiv += '<div class="slick_item" href="' + v.src + '" ><img width="100%" src="' + v.src + '" alt="' + v.title + '" /></div>';
            });
            if (this.dataThumbnail) {
                s_config['asNavFor'] = tmpDOMFor;
                this.configFor['asNavFor'] = tmpDOM;
                html = '<div class="slider-for-' + s_class + '">' + tmpImageDiv + '</div><div class="' + s_class + '-nav">' + tmpImageDiv + '</div>';
            } else {
                html = '<div class="' + s_class + '-nav">' + tmpImageDiv + '</div>';
            }

            setTimeout(() => {
                if (this.dataShowImage) {
                    $(tmpDOM).magnificPopup({
                        delegate: '.slick_item',
                        type: 'image',
                        gallery: {
                          enabled: true
                        },
                        removalDelay: 300,
                    });
                }

                $(tmpDOM).css(s_styles);
                $(tmpDOM).slick(s_config);
                $(tmpDOMFor).slick(this.configFor);
            }, 0);
            return html;
        } catch (e) {
            return 'Error: ' + e;
        }

    }
}

