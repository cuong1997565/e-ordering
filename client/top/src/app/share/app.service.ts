import {Injectable} from '@angular/core';
import {HttpClient, HttpErrorResponse} from '@angular/common/http';
import {constant} from '../config';
import {cookie} from './cookie';
import {catchError} from 'rxjs/operators';
import {throwError} from 'rxjs/internal/observable/throwError';
import * as $ from 'jquery';
import * as _ from 'lodash';

@Injectable()
export class AppService {
    public curMember: any;
    public curLang: string;

    public constant = constant;
    public filesUpload: any = [];

    public staticContent: any = []; // List of static content
    public staticList: any = []; // List of static category with posts
    public staticPost: any = []; // List of static post
    public menu: any = []; // The top menu
    public env: any = []; // System configuration from server
    public changeLimitPage: boolean = false;

    constructor(private http: HttpClient) {
        window.scrollTo(0, 0);
    }

    // This function will run after all resolve
    appResolve(res) {
        if (res[0]) {
            // Build trans dictionary
            window['langDict'] = {};
            _.forEach(res[0].trans, function (item) {
                if (typeof window['langDict'][item.lang] === 'undefined') {
                    window['langDict'][item.lang] = {};
                }

                window['langDict'][item.lang][item.key] = item.trans;
            });

            // Get static content
            _.forEach(res[0].content, (item) => {
                this.staticContent[item.code] = item.active ? item.content : '';
            });

            this.curMember = res[0].profile;
            this.staticList = res[0].list;
            this.staticPost = res[0].post;
            this.menu = res[0].menu;
            this.env = res[0].env;
        }
    }

    /* ---------- API { ---------- */
    get(url, data?) {
        if (typeof (data) === 'undefined') {
            data = {};
        }
        data['e_auth_token'] = this.getConfig('E_TOKEN', '');
        if (this.curLang) {
            data['lang'] = this.curLang;
        }

        const params = this.convertQueryString(data);
        url += '?' + params;
        $('.loadingRequest').show();
        return this.http.get(this.constant.BASE_API + url).pipe(catchError(this.handleError)).pipe(this.handleSuccess);
    }

    post(url, data, listFileFields: any = []) {
        let formData: FormData = new FormData();
        formData.append('e_auth_token', this.getConfig('E_TOKEN', ''));
        if (this.curLang) {
            formData.append('lang', this.curLang);
        }
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

        // let header = new HttpHeaders({'Content-Type':'application/json'});
        return this.http.post(this.constant.BASE_API + url, formData).pipe(catchError(this.handleError)).pipe(this.handleSuccess);
    }

    handleSuccess(res: any) {
        $('.loadingRequest').hide();
        return res;
    }

    private handleError(error: HttpErrorResponse) {
        if (error.error instanceof ErrorEvent) {
            // A client-side or network error occurred. Handle it accordingly.
        } else {
            $('.loadingRequest').hide();
            if (error.status === 400) {
                window.location.href = 'page/not-found';
            } else if (error.status === 401) {
                window.location.href = 'auth';
            } else if (error.status === 422) {
                // Remove error
                let msg = error.error['error-message'];
                if (location.pathname === '/customer/forgot-password') {
                    if (!msg && error.error.message === 'Account is blocked') {
                        msg = 'Account is blocked!';
                    } else {
                        msg = 'Please check your input data!';
                    }
                } else {
                    if (!msg && error.error.message === 'Customer does not exist') {
                        msg = 'Customer does not exist!';
                    } else {
                        msg = 'Please check your input data!';
                    }
                }

                $('.account__show-mess').remove();
                if (!$('.account__show-mess').length) {
                    var showMess = '<div class="account__show-mess">' + msg + '</div>';
                    $(showMess).insertAfter($('.show-mess'));
                }
                if (!$('.alertCustom').length) {
                    var alertHtml = '<div class="alert alert-block alertCustom alert-danger">' +
                        '<button class="close" data-dismiss="alert">×</button>' + msg + '</div>';
                    $(alertHtml).insertAfter('ele-breadcrumb');
                } else {
                    $('.alertCustom').removeClass('alert-success').addClass('alert-danger');
                    $('.alertCustom').html(msg);
                }
                $('.has-error').removeClass('has-error');
                $('small.help-block').remove();
                $('#error-message').remove();

                // Add error

                $.each(error.error, function (key, obj) {
                    let eleInput = $(`[formControlName=${key}]`);
                    if (key === 'error-message') {
                        let trans = obj;
                        $('[role="content"]').prepend(`<div id="error-message" class="alert alert-block alert-danger"><p>${trans}</p></div>`);
                    } else if (eleInput) {
                        eleInput.closest('input').addClass('has-error');

                        let trans = obj[0];
                        if (eleInput.parent('.input-group').length === 1) {
                            eleInput.parent().after(`<small class="help-block">${trans}</small>`);
                        } else {
                            if (location.pathname === '/customer/forgot-password' || location.pathname === '/customer/forgot-change' || location.pathname === '/staff') {
                                eleInput.after(`<small class="help-block" style="font-size: 100%">${trans}</small>`);
                            } else {
                                eleInput.after(`<small class="help-block">${trans}</small>`);
                            }
                        }
                    }
                });
            }

        }
        // return an observable with a user-facing error message
        return throwError('Error in api call.');
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
                $('#content').prepend(`<div class="alert alert-block alertCustom alert-success">
                <button class="close" data-dismiss="alert">×</button><i class="fa-fw fa fa-check"></i>${message}</div>`);
            } else {
                $('.alertCustom').removeClass('alert-danger').addClass('alert-success');
                $('.alertCustom').html(message);
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
                $('#content').prepend(`<div class="alert alert-block alertCustom alert-danger">
                <button class="close" data-dismiss="alert">×</button><i class="fa-fw fa fa-check"></i>${message}</div>`);
            } else {
                $('.alertCustom').removeClass('alert-success').addClass('alert-danger');
                $('.alertCustom').html(message);
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

    isURL(str) {
        var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
        return !!pattern.test(str);
    }

    getImgPath(imgSrc) {
        if (imgSrc) {
            return this.constant.FILE_MEDIA_BASE + imgSrc.substring(2, imgSrc.length - 2);
        } else {
            return 'assets/img/default.png';
        }
    }

}
