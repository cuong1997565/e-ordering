import {Injectable} from '@angular/core';
import {HttpClient, HttpErrorResponse} from '@angular/common/http';
import {constant} from '../config/base';
import {cookie} from './cookie';
import {catchError, map} from 'rxjs/operators';
import {throwError} from 'rxjs/internal/observable/throwError';
import * as $ from 'jquery';
import * as _ from 'lodash';
import {Subject} from 'rxjs';

@Injectable()
export class AppService {
    public curUser: any;
    public authToken: string;
    public curLang: string;
    public constant = constant;
    public filesUpload: any = [];
    constructor(private http: HttpClient) {
        window.scrollTo(0, 0);
        this.authToken = this.getConfig('AUTH_TOKEN', '');
    }

    // This function will run after all resolve
    appResolve(res) {
        let transRes = res[0];

        // Build trans dictionary
        window['langDict'] = {};
        _.forEach(transRes.data, function (item) {
            if (typeof window['langDict'][item.lang] == 'undefined') {
                window['langDict'][item.lang] = {};
            }

            window['langDict'][item.lang][item.key] = item.trans;
        });
    }

    langResolve(res) {
        window['listLang'] = {};
        _.forEach(res, (item) => {
            window['listLang'][item.id] = item.lang;
        });
    }

    /* ---------- API { ---------- */
    get(url, data?) {
        this.authToken = this.getConfig('AUTH_TOKEN', '');
        if (typeof (data) === 'undefined') {
            data = {};
        }
        data['token'] = this.authToken;

        let params = this.convertQueryString(data);
        url += '?' + params;
        $('.loadingRequest').show();
        return this.http.get(this.constant.BASE_API + url).pipe(catchError(this.handleError)).pipe(this.handleSuccess);
    }

    post(url, data, listFileFields: any = []) {
        this.authToken = this.getConfig('AUTH_TOKEN', '');
        let formData: FormData = new FormData();
        formData.append('token', this.authToken);

        /* ----- Upload file { ----- */
        for (var field of listFileFields) {
            if (typeof this.filesUpload[field] !== 'undefined') {
                for (let i = 0; i < this.filesUpload[field].length; i++) {
                    let file = this.filesUpload[field][i];
                    formData.append(field + '[]', file, file.name);
                }
            }
            if (typeof this.filesUpload[field] === 'undefined' || !this.filesUpload[field] || (this.filesUpload[field] && !this.filesUpload[field].length)) {
                formData.append(field, '');
            }
            this.filesUpload[field] = false;
        }
        /* ----- Upload file } ----- */

        for (let key in data) {
            /* Data transform { */
            if (data[key] === true) {
                data[key] = 1;
            }
            if (data[key] === false) {
                data[key] = 0;
            }
            if (data[key] === null) {
                data[key] = '';
            }
            if (data[key] === 'null') {
                data[key] = '';
            }

            // Convert array to json obj
            if (Object.prototype.toString.call(data[key]) === '[object Array]' && data[key].length > 0) {
                data[key] = JSON.stringify(data[key]);
            }
            /* Data transform } */
            formData.append(key, data[key]);
        }

        $('.loadingRequest').show();

        //let header = new HttpHeaders({'Content-Type':  'application/json'});
        return this.http.post(this.constant.BASE_API + url, formData).pipe(catchError(this.handleError)).pipe(this.handleSuccess);
    }

    handleSuccess(res: any) {
        $('.loadingRequest').hide();
        $('.disabledAction').removeClass('disabledAction');
        return res;
    }

    private handleError(error: HttpErrorResponse) {
        $('.disabledAction').removeClass('disabledAction');
        if (error.error instanceof ErrorEvent) {
            // A client-side or network error occurred. Handle it accordingly.
            console.error('An error occurred:', error.error.message);
        } else {
            $('.loadingRequest').hide();
            if (error.status === 400) {
                // window.location.href = 'page/not-found';
                let arrError;
                // Remove error
                if (typeof error.error !== 'object') {
                    arrError = JSON.parse(error.error.replace('﻿{', '{'));
                } else {
                    arrError = error.error;
                }

                let msg = arrError['error-message'];
                if (!msg) {
                    msg = error.error.Message;
                }


                if (!$('.alertCustom').length) {
                    let alertHtml = '<div class="alert alert-block alertCustom alert-danger"><button class="close" data-dismiss="alert">×</button><span>' + msg + '</span></div>';
                    $(alertHtml).insertAfter('ele-breadcrumb');
                } else {
                    $('.alertCustom').removeClass('alert-success').addClass('alert-danger');
                    $('.alertCustom').find('span').html(msg);
                }
                $('.has-error').removeClass('has-error');
                $('small.help-block').remove();
                $('#error-message').remove();

                // Add error
                $('.form-control, checkbox', '[formArrayName]').addClass('item-has-many');
                $.each(arrError, function (key, obj) {
                    if (key === 'error-message') {
                        let trans = obj;
                        $('[role="content"]').prepend(`<div id="error-message" class="alert alert-block alert-danger"><p>${trans}</p></div>`);
                    } else if (key.indexOf('.') !== -1) {

                        let splitKey = key.split('.');

                        if (splitKey.length === 3) {// has many
                            let [nameFormArray, number, fieldName] = splitKey;

                            // number++;
                            // let input = $('[formcontrolname='+fieldName+']:eq('+number+')');

                            let input = $('[ng-reflect-name=' + number + ']').find('[formcontrolname=' + fieldName + ']:eq(0)'); // VIP

                            input.parent().addClass('has-error');
                            input.after(`<small class="help-block">${obj[0]}</small>`);
                        }
                    } else {
                        let eleInput = $(`[formControlName=${key}]`).not('.item-has-many');
                        eleInput.closest('div').addClass('has-error');

                        let trans = obj[0];
                        if (eleInput.parent('.input-group').length === 1) {
                            eleInput.parent().after(`<small class="help-block">${trans}</small>`);
                        } else {
                            eleInput.after(`<small class="help-block">${trans}</small>`);
                        }
                    }
                });

            } else if (error.status === 401) {
                window.location.href = '/admin/auth';
            }
            else if (error.status === 440) {
                localStorage.removeItem('AUTH_TOKEN');
                window.location.href = '/admin/auth';
            }
            else if (error.status === 422) {

                let arrError;
                // Remove error
                if (typeof error.error !== 'object') {
                    arrError = JSON.parse(error.error.replace('﻿{', '{'));
                } else {
                    arrError = error.error;
                }

                let msg = arrError['error-message'];
                if (!msg) {
                    msg = 'Please check your input data!';
                }


                if (!$('.alertCustom').length) {
                    let alertHtml = '<div class="alert alert-block alertCustom alert-danger"><button class="close" data-dismiss="alert">×</button><span>' + msg + '</span></div>';
                    $(alertHtml).insertAfter('ele-breadcrumb');
                } else {
                    $('.alertCustom').removeClass('alert-success').addClass('alert-danger');
                    $('.alertCustom').find('span').html(msg);
                }
                $('.has-error').removeClass('has-error');
                $('small.help-block').remove();
                $('#error-message').remove();

                // Add error
                $('.form-control, checkbox', '[formArrayName]').addClass('item-has-many');
                $.each(arrError, function (key, obj) {
                    if (key === 'error-message') {
                        let trans = obj;
                        $('[role="content"]').prepend(`<div id="error-message" class="alert alert-block alert-danger"><p>${trans}</p></div>`);
                    } else if (key.indexOf('.') !== -1) {

                        let splitKey = key.split('.');

                        if (splitKey.length === 3) {// has many
                            let [nameFormArray, number, fieldName] = splitKey;

                            // number++;
                            // let input = $('[formcontrolname='+fieldName+']:eq('+number+')');

                            let input = $('[ng-reflect-name=' + number + ']').find('[formcontrolname=' + fieldName + ']:eq(0)'); // VIP

                            input.parent().addClass('has-error');
                            input.after(`<small class="help-block">${obj[0]}</small>`);
                        }
                    } else {
                        let eleInput = $(`[formControlName=${key}]`).not('.item-has-many');
                        eleInput.closest('div').addClass('has-error');

                        let trans = obj[0];
                        if (eleInput.parent('.input-group').length === 1) {
                            eleInput.parent().after(`<small class="help-block">${trans}</small>`);
                        } else {
                            eleInput.after(`<small class="help-block">${trans}</small>`);
                        }
                    }
                });
            } else if (error.status === 500) {
                let arrError;
                if (typeof error.error !== 'object') {
                    arrError = JSON.parse(error.error.replace('﻿{', '{'));
                } else {
                    arrError = error.error['Message'];
                }

                let msg = arrError;
                if (!$('.alertCustom').length) {
                    let alertHtml = '<div class="alert alert-block alertCustom alert-danger"><button class="close" data-dismiss="alert">×</button><span>' + msg + '</span></div>';
                    $(alertHtml).insertAfter('ele-breadcrumb');
                } else {
                    $('.alertCustom').removeClass('alert-success').addClass('alert-danger');
                    $('.alertCustom').find('span').html(msg);
                }
                $('.has-error').removeClass('has-error');
                $('small.help-block').remove();
                $('#error-message').remove();

                // Add error
                $('.form-control, checkbox', '[formArrayName]').addClass('item-has-many');

            }

        }
        // return an observable with a user-facing error message
        return throwError(error.error);
    };

    /* ---------- API } ---------- */

    /* ---------- Flash { ---------- */
    flashSuccess(message, onPage: boolean = false) {
        if (onPage) {
            // Remove all error
            $('.has-error').removeClass('has-error');
            $('small.help-block').remove();
            $('#error-message').remove();
            $('admin-flash').html('');

            if (!$('.alertCustom').length) {
                var alertHtml = '<div class="alert alert-block alertCustom alert-success"><button class="close" data-dismiss="alert">×</button><span>' + message + '</span></div>';
                $(alertHtml).insertAfter('ele-breadcrumb');
            } else {
                $('.alertCustom').removeClass('alert-danger').addClass('alert-success');
                $('.alertCustom').find('span').html(message);
            }
        } else {
            this.setConfig('ADMIN-FLASH', JSON.stringify({
                type: 'alert-success',
                message: message
            }));
        }
    }

    flashError(message, onPage: boolean = false) {
        if (onPage) {
            $('admin-flash').html('');
            if (!$('.alertCustom').length) {
                var alertHtml = '<div class="alert alert-block alertCustom alert-danger"><button class="close" data-dismiss="alert">×</button><span>' + message + '</span></div>';
                $(alertHtml).insertAfter('ele-breadcrumb');
            } else {
                $('.alertCustom').removeClass('alert-success').addClass('alert-danger');
                $('.alertCustom').find('span').html(message);
            }
        } else {
            this.setConfig('ADMIN-FLASH', JSON.stringify
            ({
                type: 'alert-danger',
                message: message
            }));
        }
    }

    /* ---------- Flash } ---------- */

    /* ---------- Config { ---------- */
    getConfig(key, defaultValue?) {
        if (cookie.isSupported()) {
            if (localStorage.getItem(key) !== null) {
                return localStorage.getItem(key);
            } else {
                return defaultValue;
            }
        } else {
            return cookie.getItem(key, defaultValue);
        }
    }

    setConfig(key, value) {
        if (cookie.isSupported()) {
            localStorage.setItem(key, value);
        } else {
            cookie.setItem(key, value);
        }
    }

    delConfig(key) {
        if (cookie.isSupported()) {
            localStorage.removeItem(key);
        } else {
            cookie.removeItem(key);
        }
    }

    /* ---------- Config } ---------- */


    arrToList(data, key, value) {
        var result = {};
        for (var index in data) {
            result[data[index][key]] = data[index][value];
        }
        ;
        return result;
    }

    convertQueryString(data) {
        var str = [];
        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                str.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
            }
        }
        return str.join('&');
    }

    getSlug(str: any, lang = '') {
        var self = this;
        if (this.isJsonString(str)) {
            str = JSON.parse(str);
            for (let key in str) {
                str[key] = lang ? self.slug(str[lang]) : self.slug(str[key]);
            }
            return JSON.stringify(str);
        }
        return JSON.stringify(this.slug(str));
    }

    slug(str) {
        var slug = str.toLowerCase();
        slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
        slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
        slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
        slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
        slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
        slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
        slug = slug.replace(/đ/gi, 'd');
        slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
        slug = slug.replace(/ /gi, '-');
        slug = slug.replace(/\-\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-/gi, '-');
        slug = slug.replace(/\-\-/gi, '-');
        slug = '@' + slug + '@';
        slug = slug.replace(/\@\-|\-\@|\@/gi, '');
        return slug;
    }

    isJsonString(text) {
        if (!isNaN(text)) {
            return false;
        }

        if (text && /^[\],:{}\s]*$/.test(text.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

            return true;
        } else {
            return false;
        }
    }
}
