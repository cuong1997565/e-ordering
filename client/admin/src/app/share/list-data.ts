import {TranslationPipe} from './translation.pipe';

declare var $: any;

export class ListData {
    private app;
    private route;
    private apiUrl;

    public result = {};

    private dataPage = 1;
    private dataFilter = {};
    private dataQuery = {sort: 'id', direction: 'desc'};
    private originSort = {};

    constructor(app, route, apiUrl, dataQuery?) {
        this.app = app;
        this.route = route;
        this.apiUrl = apiUrl;
        this.dataQuery = dataQuery || this.dataQuery;

        if (dataQuery && dataQuery.sort && dataQuery.direction) {
            this.originSort = {
                sort: dataQuery.sort,
                direction: dataQuery.direction
            };
        }

        this.route.queryParams.subscribe(params => {
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

        this.dataPage = 1;
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
        let url = href.split('?page=');
        history.pushState(null, null, url[0]);
    }

    public reset(event) {
        // Update the data
        this.dataFilter = {};
        // Reset to origin sorting
        if (this.originSort['sort']) {
            this.dataQuery['sort'] = this.originSort['sort'];
            this.dataQuery['direction'] = this.originSort['direction'];
        } else {
            delete this.dataQuery['sort'];
            delete this.dataQuery['direction'];
        }

        $('.sorting').removeClass('sorting_asc').removeClass('sorting_desc');
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
                    $('.alert-success').remove();
                    self.app.flashSuccess(new TranslationPipe().transform(res.message), true);
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
            }, 100);
        });
    }

    generateQuery(filter: Object = {}, page: number = 1, paramsQuery: Object = {}, isSearch = false) {
        let paramsDefault = {
            'paging': 1,
            'limit': 20
        };
        let paramsMergeToDefault = {};
        let filterQuery = {};
        let paramsResult = {};
        for (let key in filter) {
            if (filter[key] != '[null]' && filter[key] != '') {
                let check = key.slice(-3);
                if (check === '_id') {
                    filterQuery[key] = filter[key];
                } else {
                    filterQuery[key] = '*' + filter[key] + '*';
                }
            }
        }
        Object.assign(paramsMergeToDefault, paramsDefault, paramsQuery);
        Object.assign(paramsResult, paramsMergeToDefault, filterQuery);

        if (!isSearch) {
            paramsResult['page'] = page;
        }

        return paramsResult;
    }
}
