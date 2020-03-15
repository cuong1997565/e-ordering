import {HttpClient, HttpErrorResponse, HttpHeaders, HttpResponse} from '@angular/common/http';
import {Injectable} from '@angular/core';
import {buildQueryString} from '../utils/helpers';
import {constant} from '../../config';
import {AppService} from '../../share/app.service';
import {Store} from '@ngxs/store';
import {RequestOptions} from '@angular/http';
import {CreateDraftOrderRequest, Order, CreateOrderRequest, UpdateOrderRequest} from '../models/Order';

@Injectable({
    providedIn: 'root'
})
export class ClientService {
    public data = {};
    public etags = {};
    public serverVersion = '';
    public token = '';
    public url = '';
    public userId: string = null;
    public urlVersion = constant.API_VERSION;
    constructor(private http: HttpClient, private appService: AppService, public store: Store) {
        if (this.appService.getConfig('E_TOKEN') === undefined) {
            this.token = '';
        } else {
            this.token = this.appService.getConfig('E_TOKEN');
        }
    }
    getUrl() {
        return this.url;
    }
    setUrl(url: string) {
        this.url = url;
    }
    getToken() {
        return this.token;
    }
    setToken(token: string) {
        this.token = token;
    }
    getUserId() {
        return this.userId;
    }
    setUserId(userId: string) {
        this.userId = userId;
    }
    getBaseRoute() {
        return `${this.url}${this.urlVersion}`;
    }
    getProductsRoute() {
        return `${this.getBaseRoute()}/products`;
    }
    getUsersRoute() {
        return `${this.getBaseRoute()}/users`;
    }
    getOrdersRoute() {
        return `${this.getBaseRoute()}/orders`;
    }
    getFactoriesRoute() {
        return `${this.getBaseRoute()}/factories_client`;
    }
    getBrandsRoute() {
        return `${this.getBaseRoute()}/brands`;
    }
    getCategoriesRoute() {
        return `${this.getBaseRoute()}/categories`;
    }
    getFeatureItemsRoute() {
        return `${this.getBaseRoute()}/feature-items-about-product`;
    }
    getUserRoute(userId) {
        return `${this.getUsersRoute()}/${userId}`;
    }
    getOrderRoute(orderId) {
        return `${this.getOrdersRoute()}/${orderId}`;
    }
    getProductRoute(productId) {
        return `${this.getProductsRoute()}/${productId}`;
    }
    getFactories = async () => {
        const token = this.token;
        return this.doFetch(`${this.getFactoriesRoute()}${buildQueryString({e_auth_token: token})}`, {method: 'get'});
    }
    getFeatureItems = async () => {
        const token = this.token;
        return this.doFetch(`${this.getFeatureItemsRoute()}${buildQueryString({e_auth_token: token})}`, {method: 'get'});
    }
    getBrands = async () => {
        const token = this.token;
        return this.doFetch(`${this.getBrandsRoute()}${buildQueryString({e_auth_token: token})}`, {method: 'get'});
    }
    getCategories = async () => {
        const token = this.token;
        return this.doFetch(`${this.getCategoriesRoute()}${buildQueryString({e_auth_token: token})}`, {method: 'get'});
    }
    getProducts = async (page = 0, perPage = 10, includeTotalCount = false) => {
        return this.doFetch(`${this.getProductsRoute()}${buildQueryString({page, perPage, includeTotalCount})}`, {method: 'get'});
    }
    checkProductAmount = async (productId: string, amount: any, dataAmount: any) => {
        const token = this.token;
        return this.doFetch(`${this.getProductRoute(productId)}/checkAmount`, {method: 'post',
            body: {product_amount: amount, dataAmount: dataAmount, e_auth_token: this.token}});
    }
    getMe = async () => {
        const token = this.token;
        return this.doFetch(`${this.getUserRoute('me')}${buildQueryString({e_auth_token: token})}`, {method: 'get'});
    }
    getOrder = async (orderId) => {
        const token = this.token;
        return this.doFetch(`${this.getOrderRoute(orderId)}${buildQueryString({e_auth_token: token})}`, {method: 'get'});
    }
    createDraftOrder(data: CreateDraftOrderRequest) {
        const token = this.token;
        data['e_auth_token'] = token;
        return this.doFetch(`${this.getOrdersRoute()}/draft`, {method: 'post', body: data});
    }

    updateDrafOrder(orderId, data) {
        const token = this.token;
        data['e_auth_token'] = token;
        return this.doFetch(`${this.getOrderRoute(orderId)}/draf/update`, {method: 'post', body: data});
    }

    createOrder(data: CreateOrderRequest) {
        const  token = this.token;
        data['e_auth_token'] = token;
        return this.doFetch(`${this.getOrdersRoute()}/add`, {method: 'post', body: data});
    }


    updateOrder(orderId, data) {
        const token = this.token;
        data['e_auth_token'] = token;
        return this.doFetch(`${this.getOrderRoute(orderId)}/update`, {method: 'post', body: data});
    }

    login = async (emailOrusrname, password) => {
        const body = {
            emailOrusrname,
            password
        };
        return this.doFetch(`${this.getUsersRoute()}/login`, {method: 'post', body: body});
    }
    doFetch = async (url, options): Promise<any> => {
        const { data_res } = await this.doFetchWithResponse(url, options);
        return data_res;
    }
    doFetchWithResponse = async (url, options): Promise<any> => {
        let cachedResponse = null;
        options = {
            ...options,
            observe: 'response' as 'body', // to display the full response & as 'body' for type cast
            responseType: 'json'
        };

        if (!options.method) {
            options.method = 'get';
        }
        const method = options.method.toLowerCase();

        let response: any;
        try {
            if (method === 'post') {
                const formData: FormData = new FormData();
                if (url.indexOf('/login') !== -1) {
                    formData.append('e_auth_token', this.getToken());
                }
                // if(this.curLang) {  formData.append('lang', this.curLang); }
                // tslint:disable-next-line:forin
                for (const key in options.body) {
                    /* Data transform { */
                    if (options.body[key] === true) {
                        options.body[key] = 1;
                    }
                    if (options.body[key] === false) {
                        options.body[key] = 0;
                    }
                    if (options.body[key] === null) {
                        options.body[key] = '';
                    }
                    if (options.body[key] === 'null') {
                        options.body[key] = '';
                    }

                    // Convert array to json obj
                    if (Object.prototype.toString.call(options.body[key]) === '[object Array]' && options.body[key].length > 0) {
                        options.body[key] = JSON.stringify(options.body[key]);
                    }
                    /* Data transform } */
                    formData.append(key, options.body[key]);
                }
                response = await this.http.post(url, formData, options).toPromise();
            } else if (method === 'get') {
                const etag = this.etags[url];
                cachedResponse = this.data[''.concat(url).concat(etag)];
                if (etag) {
                    const newheaders = new HttpHeaders({
                        'If-None-Match': etag,
                    });
                    options['headers'] = newheaders;
                }
                response = await this.http[method](url, options).toPromise();
            } else {
                response = await this.http[method](url, options).toPromise();
            }

        } catch (e) {
            if (e instanceof HttpErrorResponse) {
                if (e.status === 304) {
                    return {
                        data_res: cachedResponse,
                        headers: null,
                        response,
                    };
                }
                throw new ClientError(this.getUrl(), {
                    message: e.error.Message || '',
                    server_error_id: e.error.Id || '',
                    status_code: e.error.StatusCode || '',
                    url,
                    detail_message: e.error.DetailMessage || ''

                });
            }
        }
        const headers = response.headers;
        if (response.status === 200) {
            const etag = headers.get('ETag');
            if (etag) {
                this.data[''.concat(url).concat(etag)] = response.body.data;
                this.etags[url] = etag;
            }
            const data_res = response.body.data;
            return {
                data_res,
                headers,
                response,
            };
        }
        throw new ClientError(this.getUrl(), {
            message: response.body.Message || '',
            server_error_id: response.body.error.Id,
            status_code: response.body.error.StatusCode,
            detail_message: response.body.error.DetailMessage,
            url,
        });
    }
}

export class ClientError extends Error {
    public url = '';
    public intl = {};
    public server_error_id = '';
    public detail_message = '';
    public status_code = '';
    constructor(baseUrl, data) {
        super(data.message);
        this.message = data.message;
        this.detail_message = data.detail_message;
        this.url = data.url;
        this.intl = data.intl;
        this.server_error_id = data.server_error_id;
        this.status_code = data.status_code;
        // Ensure message is treated as a property of this class when object spreading. Without this,
        // copying the object by using `{...error}` would not include the message.
        Object.defineProperty(this, 'message', {enumerable: true});
    }
}
