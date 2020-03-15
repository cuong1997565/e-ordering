import { Pipe, PipeTransform } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
declare var $:any;

@Pipe({
    name: 'shortcode',
    pure: true
})

export class ShortcodePipe implements PipeTransform {

    constructor(private sanitizer: DomSanitizer) {}

    public transform(value: any, args?: any): any {

        if (args.genre == 'slick') {
            let slick = this.slick(value, args);
            return slick;
        }
    }

    private slick(input, settings) {
        let self = this;
        let slick;
        try {
            slick = input.replace(/\[slick.*\/slick\]/gm, (result) => {
                let objectClean = result.replace("[slick ", "").replace(" /slick]", "");
                console.log(settings);
                let s_images = settings.images;
                let s_config = settings.config;
                let s_class  = settings.class;
                let s_styles = settings.styles;

                let tmpImageDiv = '';
                $.each(s_images, (k, v) => {
                    tmpImageDiv += '<div class="slick-image" href="' + v.src + '" ><img src="' + v.src + '" alt="' + v.title + '" /></div>';
                });
                let html = '<div class="' + s_class + '">' + tmpImageDiv + '</div>';
                setTimeout(() => {
                    let tmpDOM = '.' + s_class;
                    console.log(tmpDOM);
                    $(tmpDOM).magnificPopup({
                        delegate: '.slick-image',
                        type: 'image',
                        gallery: {
                          enabled: true
                        },
                        removalDelay: 300,
                    });
                    $(tmpDOM).slick(s_config);
                    $(tmpDOM).css(s_styles);
                }, 1);
                return html;
            });
        } catch (e) {
            return 'Error: ' + e;
        }
        return this.sanitizer.bypassSecurityTrustHtml(slick);
    }
}
