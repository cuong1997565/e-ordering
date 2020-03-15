import {Component, OnInit, AfterViewInit} from '@angular/core';
import * as $ from 'jquery';
import {ListData} from '../../../share/list-data';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';
import {Location} from '@angular/common';
import * as _ from 'lodash';
import {Store} from '@ngxs/store';
import {LoadingService} from '../../../share/loading.service';

import {
    ReceivedProducts,
} from '../../../store/actions/products.action';


@Component({
    selector: 'app-product',
    templateUrl: './product.component.html',
    styleUrls: ['./product.component.css']
})
export class ProductComponent implements OnInit, AfterViewInit {
    public condition = {
        factories: {},
        featureitem: {},
        categories: {}
    };
    public currentUserId: number;
    public currentOrderId: string;
    public note: string;
    public address: string;
    public type;

    public fd;
    public parentCategory = [];
    public listProduct: any = [];
    public listCategoryOne: any = [];
    public listFactories: any = [];
    public isFactory = false;
    public productDetail: any = [];
    public loading = false;
    public loadingSpinner: boolean;
    public loadingSpinnerTimeout: any;
    public keepVisionPaginate = false;
    public data = {
        featureitem_id: '',
        factory_id : '',
        category_id : '',
        code : '',
        limt : 10
    };
    public types = [
        {id: 1, name: 'Auto'},
        {id: 2, name: 'Manual'}
    ];

    constructor(public app: AppService, private route: ActivatedRoute,
                private router: Router, private location: Location,
                private store: Store, private loadingService: LoadingService) {
    }

    ngOnInit() {
        this.loadingService.keepVisionNewOrder.subscribe(val => {
            if (val) {
                this.keepVisionPaginate = true;
            }
        })
        this.fd = new FormData(this.data);

        this.route.queryParams.subscribe((queryParams: any) => {
            //get param brand_id url
            this.data.factory_id = queryParams.factory_id ? queryParams.factory_id : '';

            this.data.featureitem_id = queryParams.featureitem_id ? queryParams.featureitem_id : '';

            this.data.category_id = queryParams.category_id ? queryParams.category_id : '';

            this.data.code = queryParams.code ? queryParams.code : '';
            this.fd.setData({'code' :  this.data.code });

            this.data.limt = queryParams.limit ? queryParams.limit : 10;

            this.getProduct();
        });


        const condition = this.route.snapshot.data.condition;

        if (condition[0]) {
            this.condition.featureitem = _.orderBy(condition[0], 'name', 'asc');
        }

        if (condition[1]) {
            this.listFactories = condition[0];
            this.condition.factories = _.orderBy(condition[1], 'name', 'asc');
        }

        //get category
        this.getCategory(this.data.category_id);

    }

    ngAfterViewInit() {
        this.viewJsHTML('.select-category');

        this.viewJsHTML('.select-status');

        if (this.data.limt) {
            $('.paging-select').parent().find('.select-styled').html(this.data.limt + ' Items');
            $('.select-options').find('li').removeClass('active');
            $('ul').find('[rel=\'' + this.data.limt + '\']').addClass('active');
        }
    }

    onChangePaginate() {
        this.loadingService.shouldKeepVisionForNewOrder();
    }
    //get category
    getCategory(isParam) {
        this.app.get('v1/categories').subscribe((res: any) => {
            this.listCategoryOne = res.data;
            const newArray = [];
            for (const item of this.listCategoryOne) {
                newArray.push(item);
                if (item.categorys.length > 0) {
                    for (const child of item.categorys) {
                        newArray.push(child);
                        if (child.category_level_three.length > 0) {
                            for (const c3 of child.category_level_three) {
                                newArray.push(c3);
                                if (c3.category_level_four.length > 0) {
                                    for (const c4 of c3.category_level_four) {
                                        newArray.push(c4);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            this.parentCategory = newArray;

            $('.select-category').parent().find('.select-options li').remove();
            const className = isParam ? 'item_select' : 'item_select active';
            $('.select-category').parent().find('.select-options').append('<li class="' + className + '" rel="" >Select category</li>');

            for (const item of this.listCategoryOne) {
                if (item.parent_id === 0) {
                    // tslint:disable-next-line:max-line-length
                    if (isParam === item.id) {
                        // tslint:disable-next-line:max-line-length
                        $('.select-category').parent().find('.select-options').append('<li class="item_select active" rel="' + item.id + '">' + item.name + '</li>');
                        $('.select-category').parent().find('.select-styled').html(item.name);
                    } else {
                        // tslint:disable-next-line:max-line-length
                        $('.select-category').parent().find('.select-options').append('<li class="item_select" rel="' + item.id + '">' + item.name + '</li>');
                    }
                    item.categorys.forEach(e => {
                        if (parseInt(isParam) === e.id) {
                            $('.select-category').parent().find('.select-options').append(
                                '<li class="item_select active" rel="' + e.id + '">  - ' + e.name + '</li>');
                            $('.select-category').parent().find('.select-styled').html(e.name);
                        } else {
                            $('.select-category').parent().find('.select-options').append(
                                '<li class="item_select" rel="' + e.id + '">  - ' + e.name + '</li>');
                        }
                        if (e.category_level_three.length > 0) {
                            e.category_level_three.forEach(c3 => {
                                if (parseInt(isParam) === c3.id) {
                                    $('.select-category').parent().find('.select-options').append(
                                        '<li class="item_select active" rel="' + c3.id + '">  &nbsp;  -- ' + c3.name + '</li>');
                                    $('.select-category').parent().find('.select-styled').html(c3.name);
                                } else {
                                    $('.select-category').parent().find('.select-options').append(
                                        '<li class="item_select" rel="' + c3.id + '">  &nbsp;  -- ' + c3.name + '</li>');
                                }
                                if (c3.category_level_four.length > 0) {
                                   c3.category_level_four.forEach(c4 => {
                                       if (parseInt(isParam) === c4.id) {
                                           $('.select-category').parent().find('.select-options').append(
                                               '<li class="item_select active" rel="' + c4.id + '">  &nbsp; ' +
                                               ' &nbsp; --- ' + c4.name + '</li>');
                                           $('.select-category').parent().find('.select-styled').html(c4.name);
                                       } else {
                                           $('.select-category').parent().find('.select-options').append(
                                               '<li class="item_select" rel="' + c4.id + '">  &nbsp;  &nbsp; --- ' + c4.name + '</li>');
                                       }
                                   });
                                }
                            });
                        }
                    });
                    //set value select category
                    setTimeout(() => {
                        $('.select-category').val(isParam);
                    }, 50);
                }

            }
            /*active to the selected element*/
            $('.select-category').parent().find('.select-styled').click(function() {
                if ( $('.select-category').parent().find('.select-options .active').position().top > 100) {
                    $('.select-category').parent().find('.select-options').animate({
                        scrollTop :  $('.select-category').parent().find('.select-options .active').position().top - 10
                    },  200);
                }
            });

        });
    }

    /*get product*/
    getProduct() {
        // Don't need if here, because router subscrit and call this. And we must show empty list if not have factory_id.
        if (this.data.factory_id !== '') {
            this.loading = true;
            clearTimeout(this.loadingSpinnerTimeout);
            this.loadingSpinnerTimeout = setTimeout(() => {
                this.loadingSpinner = true;
            }, 700);
            const dataQuery = {
                factory_id: this.data.factory_id
            };

            if (this.data.featureitem_id !== '') {
                dataQuery['featureitem_id'] = this.data.featureitem_id;
            }

            if (this.data.category_id !== '') {
                dataQuery['category_id'] = this.data.category_id;
            }

            if (this.data.code !== '') {
                dataQuery['code'] = this.data.code;
            }

            if (this.data.limt) {
                dataQuery['limit'] = this.data.limt;
            }

            const urlApi = 'v1/factories/' + this.data.factory_id + '/products/search';
            if (this.listProduct.length === 0) {
                this.listProduct = new ListData(this.app, this.route, urlApi , dataQuery);
                this.listProduct.resultData.subscribe((val) => {
                    const data = _.keyBy(val.data, function (product) {
                        return product.id;
                    });
                    this.loading = false;
                    clearTimeout(this.loadingSpinnerTimeout);
                    this.loadingSpinnerTimeout = setTimeout(() => {
                        this.loadingSpinner = false;
                    }, 100);
                    this.store.dispatch(new ReceivedProducts(data));
                });
            }

            // this.listProduct.unSubscribe = true;
            // this.listProduct.unsubObs.next();
        } else {
            this.listProduct.result = {};
        }
    }

    changefactory($event: any) {
        this.data.factory_id = $event;
    }

    changeFeatureitem($event: any) {
        this.data.featureitem_id = $event;
    }

    changeCode($event: any) {
        this.data.code = $event.target.value;
    }

    slideString(name) {
        let  data = '';
        if (name.length > 10) {
            data =  name.slice(0, 10) + '...';
        } else {
            data = name;
        }
        return data;

    }



     filter() {
        this.listProduct.unSubscribe = true;
        //featureitem id
        if (this.data.featureitem_id !== null) {
            this.fd.form.value.featureitem_id = this.data.featureitem_id;
        } else {
            this.fd.form.value.featureitem_id = '';
            delete this.fd.form.value.featureitem_id;
        }

        //factory id
        if (this.data.factory_id !== null) {
            this.fd.form.value.factory_id = this.data.factory_id;
        } else {
            this.fd.form.value.factory_id = '';
            delete this.fd.form.value.factory_id;
        }

        //category
        if ($('.select-category').val()) {
            this.fd.form.value.category_id =  $('.select-category').val();
        } else {
            this.fd.form.value.category_id = '';
            delete this.fd.form.value.category_id;
        }

        //code
        if (this.data.code !== '') {
            this.fd.form.value.code = this.data.code;
        } else {
            this.fd.form.value.code = '';
            delete this.fd.form.value.code;
        }

        let param = {};
        param['limit'] = 20;
        param = Object.assign(param, this.fd.form.value);

        const url = new URL(this.removeParam('page', window.location.href));
        /*remove cac bien tren url*/
        url.searchParams.delete('factory_id');
        url.searchParams.delete('featureitem_id');
        url.searchParams.delete('category_id');
        url.searchParams.delete('code');

        if (this.fd.form.value.factory_id) {
            /*set bien code nen url*/
            url.searchParams.set('factory_id', this.fd.form.value.factory_id);
        }

        if (this.fd.form.value.featureitem_id) {
            url.searchParams.set('featureitem_id', this.fd.form.value.featureitem_id);
        }

        if (this.fd.form.value.category_id) {
            url.searchParams.set('category_id', this.fd.form.value.category_id);
        }

        if (this.fd.form.value.code) {
            url.searchParams.set('code', this.fd.form.value.code);
        }

        const newUrl = url.pathname + url.search;
        this.location.go(newUrl);

        url.searchParams.set('page', '1');


        $('html, body').animate({ scrollTop: 0 }, "slow");

        let pathName = url.pathname;

        if (url.pathname.indexOf('/admin') === 0) {
            pathName = url.pathname.replace('/admin', '');
        }

        this.isFactory = false;

        this.router.navigateByUrl(pathName + url.search);
        if (this.data.factory_id === '') {
            this.isFactory = true;
            return false;
        }
        const urlApi = 'v1/factories/' + this.data.factory_id + '/products/search';
        this.listProduct = new ListData(this.app, this.route, urlApi , param);
        this.listProduct.resultData.subscribe((val) => {
            const data = _.keyBy(val.data, function (product) {
                return product.id;
            });
            this.loading = false;
            clearTimeout(this.loadingSpinnerTimeout);
            this.loadingSpinnerTimeout = setTimeout(() => {
                this.loadingSpinner = false;
            }, 100);
            this.store.dispatch(new ReceivedProducts(data));
        });

    }

    //remove page on params
    removeParam(key, sourceURL) {
        var rtn = sourceURL.split("?")[0],
            param,
            params_arr = [],
            queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
        if (queryString !== "") {
            params_arr = queryString.split("&");
            for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                param = params_arr[i].split("=")[0];
                if (param === key) {
                    params_arr.splice(i, 1);
                }
            }
            rtn = rtn + "?" + params_arr.join("&");
        }
        return rtn;
    }
    /*get detail product*/
    getDetalProduct(id, factory_id) {
        // tslint:disable-next-line:prefer-const
        let url = 'v1/products/' + id + '/detail/' + factory_id;
        return this.app.get(url).subscribe((res: any) => {
            this.productDetail = res.data;

        });
    }

    /*check input type number*/
    IsNumeric(input) {
        return /^-?[0-9]+$/.test(input);
    }



    viewJsHTML(selector) {
        var currentSelect = $(selector);
        let tmp = selector.replace('.', '');

        var $this = currentSelect, numberOfOptions = currentSelect.children('option').length;

        $this.addClass('select-hidden');
        $this.wrap('<div class="select"></div>');
        $this.after(`<div class="select-styled real-${tmp}"></div>`);

        var $styledSelect = $this.next('div.select-styled');
        $styledSelect.text($this.children('option').eq(0).text());

        var $list = $('<ul />', {
            'class': 'select-options'
        }).insertAfter($styledSelect);

        for (var i = 0; i < numberOfOptions; i++) {
            var selected = $this.children('option').eq(i).attr('selected');
            var classSelected = '';
            if (selected === 'selected') {
                var textSelected = $this.children('option').eq(i).text();
                $this.parent().find('.select-styled').html(textSelected);
                classSelected = 'active';
            }
            $('<li />', {
                text: $this.children('option').eq(i).text(),
                rel: $this.children('option').eq(i).val(),
                class: classSelected + ' item_select ' + tmp + '-' + $this.children('option').eq(i).val()
            }).appendTo($list);
        }

        $styledSelect.click(function (e) {
            e.stopPropagation();
            $('div.select-styled.active').not(this).each(function () {
                $(this).removeClass('active').next('ul.select-options').hide();
            });
            $(this).toggleClass('active').next('ul.select-options').toggle();
        });

        $(document).click(function () {
            $styledSelect.removeClass('active');
            $list.hide();
        });

        $(document).on('click', '.item_select', function (e) {
            $(this).parent().find('.item_select').removeClass('active');
            $(this).addClass('active');
            e.stopPropagation();
            $(this).parents('.select').find('.select-styled').text($(this).text()).removeClass('active');
            $(this).parents('.select').find('select').val($(this).attr('rel'));
            $(this).parent().hide();
            $(this).parents('.select').find('select').trigger('change');
        });
    }

}
