import {Observable, Subject} from 'rxjs';
import * as _ from 'lodash';
import {takeUntil} from 'rxjs/operators';
declare var $: any;

export class ListData {
    private app;
    private route;
    private apiUrl;

    public result = {};
    public resultData = new Subject();
    private dataPage = 1;
    private dataFilter = {};
    private dataQuery = {sort: 'id', direction: 'desc'};
    private unSubscribe = false;
    public unsubObs = new Subject();
    constructor(app, route, apiUrl, dataQuery?) {
        this.app = app;
        this.route = route;
        this.apiUrl = apiUrl;
        if (typeof dataQuery !== 'undefined') {
            this.dataQuery = dataQuery;
        }

        this.route.queryParams
            .pipe(
                takeUntil(this.unsubObs)
            )
            .subscribe(params => {
            this.dataPage = params.page;
            this.getData();
        });
    }

    public sort(field, event?) {
        // Update the params
        if (this.dataQuery['sort'] === field) {
            this.dataQuery['direction'] = this.dataQuery['direction'] === 'asc' ? 'desc' : 'asc';
        } else {
            this.dataQuery['sort'] = field;
            this.dataQuery['direction'] = 'desc';
        }

        // Update the view
        if (typeof event !== 'undefined') {
            let ele = event.target || event.srcElement;

            let curTh = $(ele);
            $('.sorting').removeClass('sorting_asc').removeClass('sorting_desc');
            ele.classList.add('sorting_' + this.dataQuery['direction']);
            if (curTh.prop('tagName') === 'A') {
                curTh = curTh.parent();
            }
            curTh.parent().find('span').remove();
            curTh.append('<span class="arrow ' + this.dataQuery['direction'] + '"></span>');
        } else {
            console.log('Hey dev! add the $event to the second param to have the arrows up/down');
        }

        this.getData();
    }

    public change(field, event) {
        let ele = event.target || event.srcElement;
        let curTb = $(ele);

        if (curTb.val() && curTb.val() != 'null') {
            this.dataFilter[field] = curTb.val();
        } else {
            delete this.dataFilter[field];
        }

        this.getData(true);

        // Remove the page params
        let href = window.location.href;
        let url = href.split(';page=');
        history.pushState(null, null, url[0]);
    }

    public reset(event) {
        // Update the data
        this.dataFilter = {};
        this.getData();

        // Update the view
        let ele = event.target || event.srcElement;
        let curTh = $(ele);
        curTh.closest('tr').find('input,select').val('');
    }

    public delete(apiUrl, item) {
        let self = this;
        $.SmartMessageBox({
            title: '<i class=\'fa fa-trash\' style=\'color:red\'></i> Delete item confirmation',
            content: 'Are you sure?',
            buttons: '[No][Yes]'
        }, function (ButtonPressed) {
            if (ButtonPressed === 'Yes') {
                self.app.post(apiUrl, {id: item.id}).subscribe((res: any) => {
                    self.getData();
                });
            }
        });
    }

    public setQuery(obj) {
        this.dataQuery = obj;
    }

    getData(isSearch = false) {
        const paramsApi = this.generateQuery(this.dataFilter, this.dataPage, this.dataQuery, isSearch);
        this.app.get(this.apiUrl, paramsApi).subscribe((res: any) => {
            setTimeout(() => {
                this.result = res.data;
                this.resultData.next(res.data);
            }, 100);
        });
    }

    generateQuery(filter: Object = {}, page: number = 1, paramsQuery: Object = {}, isSearch = false) {
        let paramsDefault = {
            'paging': 1,
            'limit': 10
        };
        let paramsMergeToDefault = {};
        let filterQuery = {};
        let paramsResult = {};
        for (let key in filter) {
            if (filter[key] != '[null]' && filter[key] != '') {
                filterQuery[key] = '*' + filter[key] + '*';
            }
        }
        Object.assign(paramsMergeToDefault, paramsDefault, paramsQuery);
        Object.assign(paramsResult, paramsMergeToDefault, filterQuery);
        paramsResult['page'] = page;

        if (!isSearch) {
            paramsResult['page'] = page;
        }

        if (this.app.changeLimitPage) {
            paramsResult['page'] = 1;
            this.app.changeLimitPage = false;
        }
        return paramsResult;
    }
}
