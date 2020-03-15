import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {AppService} from '../../../share/app.service';
import {ListData} from '../../../share/list-data';
import {FormData} from '../../../share/form-data';
import {Location} from '@angular/common';
import * as _ from 'lodash';
import {LoadingService} from '../../../share/loading.service';
import {UserState} from '../../../store/user.state';
import {Store} from '@ngxs/store';

declare var $: any;

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {

    constructor(public app: AppService,
                private route: ActivatedRoute,
                private location: Location,
                private loadingService: LoadingService,
                private store: Store) {
        this.store.select(UserState.getCurrentUser).subscribe((val) => {
            this.distributor_id = val.distributor_id;
            this.check = val.is_admin;
        });
    }

    public ld;
    public distributor_id;
    public fd;
    public check;
    public is_admin;
    public numberPage: number = 10;
    public queryParams: any;
    private data = {
        name: '',
        is_admin: ''
    };
    public param = {
        page: 1,
        paging: 1,
    };
    public role = [
        {id: 3, name: 'Order Manager'},
        {id: 2, name: 'Order Viewer'},
    ];
    public fetchListLoading: any;
    public loading: boolean;

    ngOnInit() {
        this.fetchListLoading = setTimeout(() => {
            this.loading = true;
        }, 500);
        this.fd = new FormData(this.data);
        this.param['distributor_id'] = this.distributor_id;
        this.route.queryParams.subscribe((queryParams: any) => {
            this.queryParams = queryParams;
            const is_admin = queryParams.is_admin ? queryParams.is_admin : false;
            if (is_admin) {
                this.param['is_admin'] = is_admin;
                this.data.is_admin = is_admin;
                this.is_admin = is_admin;
            }

            const name = queryParams.name ? queryParams.name : false;
            if (name) {
                this.fd.setData({'name': name});
                this.data.name = name;
                this.param['name'] = name;
            }

            const distributor_id = queryParams.distributor_id ? queryParams.distributor_id : false;
            if (distributor_id) {
                this.fd.setData({'distributor_id': distributor_id});
                this.data.name = distributor_id;
                this.param['distributor_id'] = distributor_id;
            }

        });
        this.getUser();
        this.changeLimit();
        this.viewJsHtml();

        let limit = this.route.snapshot.queryParams['limit'];
        if (limit) {
            $('.paging-select').parent().find('.select-styled').html(limit + ' Items');
            $('.select-options').find('li').removeClass('active');
            $('ul').find('[rel=\'' + limit + '\']').addClass('active');
        }

        if (this.check === this.app.constant.ORDER_MANAGER || this.check === this.app.constant.ORDER_VIEWER) {
            $('.button_black1').hide();
        }
    }

    getUser() {
        this.ld = new ListData(this.app, this.route, 'v1/customers', {distributor_id: this.distributor_id});
        this.ld.resultData.subscribe(() => {
            clearTimeout(this.fetchListLoading);
            this.loading = false;
        });
    }

    changeLimit() {
        const self = this;
        $('.paging-select').change(function () {
            const value = Number($(this).val());
            if (value) {
                self.app.changeLimitPage = true;
                self.numberPage = value;
                self.param['limit'] = value;
                const url = new URL(window.location.href);
                url.searchParams.set('limit', String(self.param['limit']));
                url.searchParams.set('page', String(1));
                url.searchParams.set('paging', String(1));
                const newUrl = url.pathname + url.search;
                self.location.go(newUrl);
                self.ld = new ListData(self.app, self.route, 'v1/customer_search', self.param);
            }
        });
    }

    changeActive(event: any, id) {
        this.app.post('v1/customers/changeActiveClient', {active: event, id: id}).subscribe((res: any) => {
        });
    }

    changeRole(e) {
        this.data.is_admin = e;
    }

    search() {
        // factory
        if (this.data.is_admin === '' || this.data.is_admin === null) {
            this.fd.form.value.is_admin = '';
            delete this.fd.form.value.is_admin;
        } else {
            this.fd.form.value.is_admin = this.data.is_admin;
        }

        // name
        if (this.fd.form.value.name === '') {
            this.fd.form.value.name = '';
            delete this.fd.form.value.name;
        }

        let param = {};
        param['distributor_id'] = this.distributor_id;
        param['limit'] = this.param['limit'] ? this.param['limit'] : 10;
        param = Object.assign(param, this.fd.form.value);

        const url = new URL(this.removeParam('page', window.location.href));
        url.searchParams.delete('is_admin');
        url.searchParams.delete('name');
        url.searchParams.delete('distributor_id');

        if (param['is_admin']) {
            url.searchParams.set('is_admin', this.fd.form.value.is_admin);
            this.param['is_admin'] = this.fd.form.value.is_admin;
        } else {
            this.param = _.omit(this.param, ['is_admin']);
        }

        if (param['name']) {
            url.searchParams.set('name', this.fd.form.value.name);
            this.param['name'] = this.fd.form.value.name;
        } else {
            this.param = _.omit(this.param, ['name']);
        }

        if (param['distributor_id']) {
            url.searchParams.set('distributor_id', this.distributor_id);
            this.param['distributor_id'] = this.distributor_id;
        }
        const newUrl = url.pathname + url.search;
        this.location.go(newUrl);

        this.ld = new ListData(this.app, this.route, 'v1/customer_search', param);
    }

    removeParam(key, sourceURL) {
        var rtn = sourceURL.split('?')[0],
            param,
            params_arr = [],
            queryString = (sourceURL.indexOf('?') !== -1) ? sourceURL.split('?')[1] : '';
        if (queryString !== '') {
            params_arr = queryString.split('&');
            for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                param = params_arr[i].split('=')[0];
                if (param === key) {
                    params_arr.splice(i, 1);
                }
            }
            rtn = rtn + '?' + params_arr.join('&');
        }
        return rtn;
    }

    viewJsHtml() {
        $('.select-cus').each(function () {
            var $this = $(this), numberOfOptions = $(this).children('option').length;

            $this.addClass('select-hidden');
            $this.wrap('<div class="select"></div>');
            $this.after('<div class="select-styled"></div>');

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
                    class: classSelected
                }).appendTo($list);
            }

            var $listItems = $list.children('li');

            $styledSelect.click(function (e) {
                e.stopPropagation();
                $('div.select-styled.active').not(this).each(function () {
                    $(this).removeClass('active').next('ul.select-options').hide();
                });
                $(this).toggleClass('active').next('ul.select-options').toggle();
            });

            $listItems.click(function (e) {
                $listItems.removeClass('active');
                $(this).addClass('active');
                e.stopPropagation();
                $styledSelect.text($(this).text()).removeClass('active');
                $this.val($(this).attr('rel'));
                $list.hide();
                $(this).parents('.select').find('select').trigger('change');
            });

            $(document).click(function () {
                $styledSelect.removeClass('active');
                $list.hide();
            });

        });
    }

}
