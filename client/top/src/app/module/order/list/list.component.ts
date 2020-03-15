import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, NavigationEnd, Router} from '@angular/router';
import {AppService} from '../../../share/app.service';
import {FormData} from '../../../share/form-data';
import {ListData} from '../../../share/list-data';
import {map} from 'rxjs/operators';
import {Location} from '@angular/common';
import * as _ from 'lodash';
import * as moment from 'moment';
import {RemoveAllSelectedProductFromOrder} from '../../../store/actions/products.action';
import {OrderState} from '../../../store/order.state';
import {Store} from '@ngxs/store';
import {RemoveCurrentOrderId} from '../../../store/actions/orders.action';
import {LoadingService} from '../../../share/loading.service';
import {UserState} from '../../../store/user.state';

declare var $: any;

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {
    private data = {
        from_date1: moment().startOf('month').format('YYYY-MM-DD'),
        to_date1: moment().endOf('month').format('YYYY-MM-DD'),
        from_date2: '',
        to_date2: '',
        factory_id: '',
        status: '',
        order_code: ''
    };
    public currentOrderId: string;
    public distributorId: number;
    public availableAmount = 0;
    public fromDate: any = '';
    public toDate: any = '';
    public fd;
    public factory_id;
    public status_id;
    public param = {
        page: 1,
        paging: 1,
    };
    public ld;
    public check;
    public fetchListLoading: any;
    public loading: boolean;
    public factory: any;
    public numberPage: number = 10;
    public status = [
        {id: 2, name: 'Reviewing'},
        {id: 3, name: 'Closed'},
        {id: 4, name: 'Rejected'},
        {id: 5, name: 'Cancelled'},
        {id: 6, name: 'Accepted'},
        {id: 7, name: 'Delivering'},
        {id: 8, name: 'Submited'},
        
        {id: 9, name: 'Draft'},
    ];
    public queryParams: any;

    constructor(public app: AppService, private route: ActivatedRoute, private router: Router,
                private location: Location, private store: Store,
                private loadingService: LoadingService) {
        this.store.select(OrderState.getCurrentOrder).subscribe((val) => {
            this.currentOrderId = val;
        });

        this.store.select(UserState.getCurrentUser).subscribe((val) => {
            this.distributorId = val.distributor_id;
            this.check = val.is_admin;
        });
        // this.store.select(OrderViewState.getLoadingList).subscribe((val) => {
        //     this.loading = val;
        // });
        // this.loadingService.fetchLoadingListOrder();

        // this.store.dispatch(new ShowLoadingListOrderView());
    }

    ngOnInit() {
        this.fetchListLoading = setTimeout(() => {
            this.loading = true;
        }, 500);
        this.store.dispatch(new RemoveAllSelectedProductFromOrder({orderId: this.currentOrderId}));
        this.fd = new FormData(this.data);
        const condition = this.route.snapshot.data.condition;

        if (condition[1]) {
            this.factory = _.orderBy(condition[1], ['name'], ['asc']);
        }

        this.route.queryParams.subscribe((queryParams: any) => {
            this.queryParams = queryParams;
            const factory_id = queryParams.factory_id ? queryParams.factory_id : false;
            if (factory_id) {
                this.param['factory_id'] = factory_id;
                this.data.factory_id = factory_id;
                this.factory_id = factory_id;
            }

            const status = queryParams.status ? queryParams.status : false;
            if (status) {
                this.param['status'] = status;
                this.data.status = factory_id;
                this.status_id = status;
            }

            const order_code = queryParams.order_code ? queryParams.order_code : false;
            if (order_code) {
                this.fd.setData({'order_code': order_code});
                this.data.order_code = order_code;
                this.param['order_code'] = order_code;
            }

            const from_date1 = queryParams.from_date1 ? queryParams.from_date1 : false;
            if (from_date1) {
                this.param['from_date1'] = from_date1;
                this.data.from_date1 = from_date1;
                this.fd.setData({'from_date1': from_date1});
            }

            const to_date1 = queryParams.to_date1 ? queryParams.to_date1 : false;
            if (to_date1) {
                this.param['to_date1'] = to_date1;
                this.data.to_date1 = to_date1;
                this.fd.setData({'to_date1': to_date1});
            }

            const from_date2 = queryParams.from_date2 ? queryParams.from_date2 : false;
            if (from_date2) {
                this.param['from_date2;'] = from_date2;
                this.data.from_date2 = from_date2;
                this.fd.setData({'from_date2': from_date2});
            }

            const to_date2 = queryParams.to_date2 ? queryParams.to_date2 : false;
            if (to_date2) {
                this.param['to_date2'] = to_date2;
                this.data.to_date2 = to_date2;
                this.fd.setData({'to_date2': to_date2});
            }

        });
        this.changeLimit();
        this.getAvailableAmount();
        this.getOrder();

        this.viewJsHtml();

        let limit = this.route.snapshot.queryParams['limit'];
        if (limit) {
            $('.paging-select').parent().find('.select-styled').html(limit + ' Items');
            $('.select-options').find('li').removeClass('active');
            $('ul').find('[rel=\'' + limit + '\']').addClass('active');
        }

        let self = this;
        $(document).ready(function () {
            $('.datepicker').on('keyup', function (e) {
                if (e.which === 8 || e.which === 46) {
                    $(this).datepicker('setDate', null);
                    self.data.from_date1 = '';
                    self.data.to_date1 = '';
                } else {
                    if ($(this).hasClass('from_date')) {
                        $(this).datepicker('setDate', self.data.from_date1);
                    } else {
                        $(this).datepicker('setDate', self.data.to_date1);
                    }
                    // return false;
                }
            });

        });

        if (this.check === this.app.constant.ORDER_VIEWER) {
            $('.button_black1').hide();
            $('.cancel_order').hide();
        }

    }


    getAvailableAmount () {
        this.app.get('v1/get-some-field-credit-account/' + this.distributorId).subscribe((res) => {
            // @ts-ignore
            this.availableAmount = res.data.available_amount;
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
                self.ld = new ListData(self.app, self.route, 'v1/orders', self.param);
            }
        });
    }

    getOrder() {
        // this.param['from_date1'] = this.data.from_date1;
        // this.param['to_date1'] = this.data.to_date1;
        this.param['distributor_id'] = this.distributorId;
        this.ld = new ListData(this.app, this.route, 'v1/order_sreach', this.param);
        this.ld.resultData.subscribe(() => {
            clearTimeout(this.fetchListLoading);
            this.loading = false;
        });
    }

    changefactory($event: any) {
        this.data.factory_id = $event;
    }

    changeStatus($event) {
        this.data.status = $event;
    }

    ngAfterViewInit() {
        $('.datepicker').datepicker('destroy');
        $('#ui-datepicker-div').remove();
        $('.from_date').datepicker({
            prevText: '<<',
            nextText: '>>',
            dateFormat: 'yy-mm-dd',
            onSelect: function () {
                var minDate = $(this).datepicker('getDate');
                $('.to_date').datepicker('option', 'minDate', minDate);
            }
        });

        $('.to_date').datepicker({
            prevText: '<<',
            nextText: '>>',
            dateFormat: 'yy-mm-dd',
            onSelect: function () {
                var maxDate = $(this).datepicker('getDate');
                $('.from_date').datepicker('option', 'maxDate', maxDate);
            }
        });

    }

    deleteOrder(id) {
        if (confirm('Can you delete the order  page?')) {
            const url = 'v1/orders/' + id + '/delete';
            // @ts-ignore
            this.app.post(url).subscribe((data: any) => {
                alert('Delete order successfully');
                location.reload();
            });
        }
    }

    search() {
        // factory
        if (this.data.factory_id === '' || this.data.factory_id === null) {
            this.fd.form.value.factory_id = '';
            delete this.fd.form.value.factory_id;
        } else {
            this.fd.form.value.factory_id = this.data.factory_id;
        }

        // status
        if (this.data.status === '' || this.data.status === null) {
            this.fd.form.value.status = '';
            delete this.fd.form.value.status;
        } else {
            this.fd.form.value.status = this.data.status;
        }

        // order_code
        if (this.fd.form.value.order_code === '') {
            this.fd.form.value.order_code = '';
            delete this.fd.form.value.order_code;
        }

        // order_date
        if ($('#from_date1').val()) {
            this.fd.form.value.from_date1 = $('#from_date1').val();

        } else {
            this.fd.form.value.from_date1 = '';
            delete this.fd.form.value.from_date1;
        }
        if ($('#to_date1').val()) {
            this.fd.form.value.to_date1 = $('#to_date1').val();

        } else {
            this.fd.form.value.to_date1 = '';
            delete this.fd.form.value.to_date1;
        }

        // update_date
        if ($('#from_date2').val()) {
            this.fd.form.value.from_date2 = $('#from_date2').val();

        } else {
            this.fd.form.value.from_date2 = '';
            delete this.fd.form.value.from_date2;
        }
        if ($('#to_date2').val()) {
            this.fd.form.value.to_date2 = $('#to_date2').val();

        } else {
            this.fd.form.value.to_date2 = '';
            delete this.fd.form.value.to_date2;
        }

        let param = {};
        param['limit'] = this.param['limit'] ? this.param['limit'] : 10;
        param = Object.assign(param, this.fd.form.value);

        const url = new URL(this.removeParam('page', window.location.href));
        url.searchParams.delete('factory_id');
        url.searchParams.delete('status');
        url.searchParams.delete('order_code');
        url.searchParams.delete('from_date1');
        url.searchParams.delete('to_date1');
        url.searchParams.delete('from_date2');
        url.searchParams.delete('to_date2');

        if (param['factory_id']) {
            url.searchParams.set('factory_id', this.fd.form.value.factory_id);
            this.param['factory_id'] = this.fd.form.value.factory_id;
        } else {
            this.param = _.omit(this.param, ['factory_id']);
        }


        if (param['status']) {
            url.searchParams.set('status', this.fd.form.value.status);
            this.param['status'] = this.fd.form.value.status;
        } else {
            this.param = _.omit(this.param, ['status']);
        }

        if (param['order_code']) {
            url.searchParams.set('order_code', this.fd.form.value.order_code);
            this.param['order_code'] = this.fd.form.value.order_code;
        } else {
            this.param = _.omit(this.param, ['order_code']);
        }

        if (param['from_date1']) {
            url.searchParams.set('from_date1', this.fd.form.value.from_date1);
            this.param['from_date1'] = this.fd.form.value.from_date1;
        } else {
            this.param = _.omit(this.param, ['from_date1']);
        }

        if (param['to_date1']) {
            url.searchParams.set('to_date1', this.fd.form.value.to_date1);
            this.param['to_date1'] = this.fd.form.value.to_date1;
        } else {
            this.param = _.omit(this.param, ['to_date1']);
        }

        if (param['from_date2']) {
            url.searchParams.set('from_date2', this.fd.form.value.from_date2);
            this.param['from_date2'] = this.fd.form.value.from_date2;
        } else {
            this.param = _.omit(this.param, ['from_date2']);
        }

        if (param['to_date2']) {
            url.searchParams.set('to_date2', this.fd.form.value.to_date2);
            this.param['to_date2'] = this.fd.form.value.to_date2;
        } else {
            this.param = _.omit(this.param, ['to_date2']);
        }

        const newUrl = url.pathname + url.search;
        this.location.go(newUrl);

        this.ld = new ListData(this.app, this.route, 'v1/order_sreach', param);
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

    viewOrder(id) {
        // this.loadingService.fetchLoadingListOrder();
        // this.store.dispatch(new ShowLoadingListOrderView());
        this.router.navigate(['order/view/' + id]);
    }

    goToCreateOrder() {
        this.store.dispatch(new RemoveAllSelectedProductFromOrder({orderId: this.currentOrderId}));
        this.store.dispatch(new RemoveCurrentOrderId());
        // this.loadingService.fetchLoadingListOrder();
        // this.store.dispatch(new ShowLoadingListOrderView());
        // this.store.dispatch(new ShowLoadingNewOrderView());
        this.router.navigate(['/order/add']);
    }

    cancelOrder(currentOrderId) {
        if (confirm('Can you change the order status page?')) {
            const url = 'v1/orders/' + currentOrderId + '/update/status';
            // @ts-ignore
            this.app.post(url).subscribe((data: any) => {
                alert('Change order status successfully');
                location.reload();
            }, (error: any) => {
                alert('Can not update order');
                location.reload();
            });
        }
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
