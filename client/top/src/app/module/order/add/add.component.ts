import {Component, OnInit, ɵConsole, AfterViewInit} from '@angular/core';
import * as $ from 'jquery';
import {ActivatedRoute, Router} from '@angular/router';
import { throwError } from 'rxjs';
import { ListData } from '../../../share/list-data';
import {FormData} from '../../../share/form-data';
import {AppService} from '../../../share/app.service';
import * as _ from 'lodash';
import {Location} from '@angular/common';
import {Select, Store} from '@ngxs/store';
import {
    AddProductToOrder,
    ChangeAmountProductFromOrder, CheckAllAmountProductsInOrder, NoteProductFromOrder,
    ReceivedProducts, RecoverProductInOrder, RecoverProductInOrderPayload, RemoveAllSelectedProductFromOrder,
    RemoveSelectedProductFromOrder
} from '../../../store/actions/products.action';
import {UserState} from '../../../store/user.state';
import {ProductSelectedInterface, ProductState} from '../../../store/product.state';
import {OrderState} from '../../../store/order.state';
import {Product} from '../../../store/models/Product';
import {CreateDraftOrderRequest, Order} from '../../../store/models/Order';
import {CreateDraftOrder} from '../../../store/actions/orders.action';
import {LoadingService} from '../../../share/loading.service';
import {element} from 'protractor';
declare var $: any;

@Component({
    selector: 'app-add',
    templateUrl: './add.component.html',
    styleUrls: ['./add.component.css']
})
export class AddComponent implements OnInit, AfterViewInit {
    public condition = {
        factories: {},
        featureitem: {},
        categories: {}
    };
    public currentUserId: number;
    public currentOrderId: string;
    public note: string;
    public address: string;
    public type = 1;
    public currentUserDistributorId: number;
    public currentUserDitributorCode: string;

    public currentProductsSelected: ProductSelectedInterface[];
    public currentProductSelectedIds: number[];
    public listProductsFromStore: { [key: string]: Product };
    public inputAmount = 1;
    public inputGrade = null;
    public inputUom = null;
    public inputUomName = '';
    public isDisabled = false;
    public isDisabledSubmit = false;
    public isDisabledSave = false;
    public fd;
    public parentCategory = [];
    public listProduct: any = [];
    public listCategoryOne: any = [];
    public listFactories: any = [];
    public isFactory = false;
    public productDetail: any = [];
    public dataListGrade: any = [];
    public dataListAttribute: any = [];
    public dataListUom: any = [];
    public isConfirm = false;
    public isInputGrade = false;
    public isInputUom = false;
    public isInputAmount = false;
    public loading = false;
    public loadingSpinner: boolean;
    public loadingSpinnerTimeout: any;
    public keepVisionPaginate = false;
    public arrayAttributeList: any = [];
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
        window.scrollTo(0, 0);

        this.store.select(OrderState.getCurrentOrder).subscribe((val) => {
            this.currentOrderId = val;
        });

        this.store.select(ProductState.getSelectedProductForOrder).subscribe(selectedProducts => {
            const newSort = Object.values(Object.assign({}, selectedProducts));
            if (newSort.length === 0) {
                this.isDisabled = false;
                this.isDisabledSubmit = true;
                this.isDisabledSave = true;
            } else {
                this.isDisabled = true;
                this.isDisabledSubmit = false;
                this.isDisabledSave = false;
            }
            newSort.sort((a, b) => {
                const aStartsAt = a.timeAdd;
                const bStartsAt = b.timeAdd;
                return bStartsAt - aStartsAt;
            });
            this.currentProductsSelected = newSort;

        });

        this.store.select(ProductState.getProducts).subscribe(products => {
            this.listProductsFromStore = products;
        });

        this.store.select(UserState.getCurrentUserId).subscribe(id => {
            this.currentUserId = id;
        });

        this.store.select(ProductState.collectIdSeltectedProduct(this.currentOrderId)).subscribe(ids => {
            this.currentProductSelectedIds = ids;
        });

        this.store.select(UserState.getCurrentDitributorUser).subscribe(id => {
            this.currentUserDistributorId = id;
        });

        this.store.select(UserState.getCurrentDitributorCodeUser).subscribe(code => {
            this.currentUserDitributorCode = code;
        });
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

            this.data.limt = queryParams.limit ? queryParams.limit : 20;
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
        this.getProduct();
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

    changeInputNote($event: any) {
        this.note = $event.target.value;
    }

    changeInputAddress($event: any) {
        this.address = $event.target.value;
    }

    changeType($event: any) {
        this.type = $event;
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
        this.inputAmount = 1;
        this.inputGrade = null;
        this.inputUom = null;
        this.isInputGrade = false;
        this.isInputUom = false;
        this.isInputAmount = false;
        this.arrayAttributeList = [];
        this.inputUomName = '';
        // tslint:disable-next-line:prefer-const
        let url = 'v1/products/' + id + '/detail/' + factory_id;
        return this.app.get(url).subscribe((res: any) => {
            this.productDetail = res.data;
            this.listGrade();
            this.listUom();
            this.listAttribute();

        });
    }

    /*
    * list grade about product grade_group
    * */
    listGrade() {
        const url = 'v1/grade-items/' + this.productDetail.grade_group_id;
        // @ts-ignore
        return this.app.get(url).subscribe((res: any) => {
            this.dataListGrade = res.data;
        });
    }
    /*
    * list attribute
    * */
    listAttribute() {
        const url = 'v1/attribute-item/' + this.productDetail.product_type_id;
        return this.app.get(url).subscribe((res: any) => {
            this.dataListAttribute = res.data;
            if (res.data.length > 0) {
                for (let i = 0; i < res.data.length; i++) {
                    this.arrayAttributeList.push({
                        'id' : res.data[i].id,
                        'attribute_label' : null,
                        'name': res.data[i].name,
                        'code' : res.data[i].code,
                        'sequence' : res.data[i].sequence,
                        'type' : res.data[i].type,
                        'attribute_name' : null
                    });
                }
            }
        });
    }
    /*
       * list uom
    * */
    listUom() {
        const url = 'v1/uom/' + this.productDetail.uom_id;
        return this.app.get(url).subscribe((res: any) => {
            this.dataListUom = res.data;

        });
    }

    getValueAttribute(e, index) {
        this.arrayAttributeList[index].attribute_label = e.target.value;
        if (this.arrayAttributeList[index].type === this.app.constant.Attributes_Type_List) {
            // get value attribute
            // tslint:disable-next-line:radix no-shadowed-variable
            const attribute = this.dataListAttribute[index].attributelist.find(element => element.id === parseInt(e.target.value));
            this.arrayAttributeList[index].attribute_name = attribute.value;
        }
    }

    /*check input type number*/
    IsNumeric(input) {
        return /[0-9]|\./.test(input);
    }

    removeSelectedProduct(code: string) {
        this.store.dispatch(new RemoveSelectedProductFromOrder({orderId: this.currentOrderId, code: code}));
    }

    validatorDetailProduct(productDetail: any) {

    }
    onSelectProduct(productDetail: any) {
        this.data.factory_id = productDetail.factory_id;

        // validate product
        if (!this.IsNumeric(this.inputAmount)) {
            this.isInputAmount = true;
            return  false;
        } else {
            this.isInputAmount = false;
        }
        if (this.inputAmount < 0) {
            this.isInputAmount = true;
            return  false;
        } else {
            this.isInputAmount = false;
        }


        if (this.inputGrade === null) {
            this.isInputGrade = true;
            return  false;
        } else {
            this.isInputGrade = false;
        }

        if (this.inputUom == null) {
            this.isInputUom = true;
            return  false;
        } else {
            this.isInputUom = false;
        }

        for (let i = 0; i < this.arrayAttributeList.length; i++) {
            // tslint:disable-next-line:radix
            if (this.arrayAttributeList[i].type === parseInt(this.app.constant.Attributes_Type_List)) {
                if (this.arrayAttributeList[i].attribute_label === null || this.arrayAttributeList[i].attribute_label === 'null') {
                    $('.error-attribute-' + i).html('Please select a attributes');
                    return false;
                } else {
                    $('.error-attribute-' + i).html('');
                }
            }
            // tslint:disable-next-line:radix
            if (this.arrayAttributeList[i].type === parseInt(this.app.constant.Attributes_Type_Number)) {
                if (this.arrayAttributeList[i].attribute_label == null) {
                    $('.error-attribute-' + i).html('Please enter input');
                    return  false;
                } else if (!this.IsNumeric(this.arrayAttributeList[i].attribute_label)) {
                    $('.error-attribute-' + i).html('Input field is invalid.');
                    return false;
                } else if (this.arrayAttributeList[i].attribute_label < 0) {
                    $('.error-attribute-' + i).html('Input field is invalid.');
                    return false;
                } else {
                    $('.error-attribute-' + i).html('');
                }
            }

            // tslint:disable-next-line:radix
            if (this.arrayAttributeList[i].type === parseInt(this.app.constant.Attributes_Type_String)) {
                if (this.arrayAttributeList[i].attribute_label == null || this.arrayAttributeList[i].attribute_label === '') {
                    $('.error-attribute-' + i).html('Please enter input');
                    return  false;
                } else {
                    $('.error-attribute-' + i).html('');
                }
            }
        }
        // end validator

        // sort sequence
        this.arrayAttributeList.sort((a, b) => {
            if (a.sequence < b.sequence) {
                return -1;
            }
            if (a.sequence > b.sequence) {
                return 1;
            }
            return 0;
        });
        // end sort sequence

        // create code sku
        const dataCheckAmount = new Array();
        dataCheckAmount.push(productDetail.store_code);

        var data  = productDetail.code;

        var codeCheckAmount = productDetail.store_code.substring(0, 2);
        codeCheckAmount += '.' + this.arrayAttributeList[0].attribute_name + '.' + '09' + productDetail.code;
        for (let i = 0 ; i < this.arrayAttributeList.length; i++) {
            data  +=  '.' + this.arrayAttributeList[i].code;
        }

        dataCheckAmount.push(codeCheckAmount);
        // push attribute data check amount
        for (let i = 1 ; i < this.arrayAttributeList.length; i++) {
            if (this.arrayAttributeList[i].type === this.app.constant.Attributes_Type_List) {
                dataCheckAmount.push(this.arrayAttributeList[i].attribute_name);
            } else  {
                const attribute_label = this.arrayAttributeList[i].attribute_label !== null
                    ? this.arrayAttributeList[i].attribute_label : '';
                dataCheckAmount.push(attribute_label);

            }
        }

        // xu ly date release
        const d = productDetail.release_date.split('-');
        const d2 = d[2].split(' ');
        const relese_date = d2[0] + d[1] + d[0].toString().substr(-2)
        dataCheckAmount.push(relese_date);

        dataCheckAmount.push('CH');


        // tslint:disable-next-line:radix no-shadowed-variable
        const uom = this.dataListUom.find(element => element.id === parseInt(this.inputUom));

        /// Mã đơn vị tính
        if (uom.based_uom_id === null) {
            dataCheckAmount.push(uom.code);
        } else {
            // tslint:disable-next-line:no-shadowed-variable radix
            const based_uom = this.dataListUom.find(element => element.id === parseInt(uom.based_uom_id));
            dataCheckAmount.push(based_uom.code);
        }
        this.inputUomName = uom.name;

        dataCheckAmount.push(this.inputAmount);
        dataCheckAmount.push(this.app.constant.CODE_PRIME);

        // end create code sku

        // tslint:disable-next-line:max-line-length
        this.store.dispatch(new AddProductToOrder({orderId: this.currentOrderId, amount: this.inputAmount,
            productId: productDetail.id, code: data, gradeId: this.inputGrade,
            distributorId: this.currentUserDistributorId, attributeId: this.arrayAttributeList, uomId: this.inputUom,
            factoryId: this.data.factory_id, statusItem: this.app.constant.WAITING_FOR_DRAF_ITEM_ORDER,
            uomName: this.inputUomName, dataCheckAmount: dataCheckAmount}));

        this.inputAmount = 1;

        $('#myModal').modal('hide');

        return true;
    }
    changeAmountProduct(code: string, amount: any) {
        this.store.dispatch(new ChangeAmountProductFromOrder({orderId: this.currentOrderId, amount: amount, code: code}));
    }

    noteProductToOrder(code: string, note: string) {
        this.store.dispatch(new NoteProductFromOrder({orderId: this.currentOrderId, userNote: note, code: code}));
    }
    incrementAmount(code, currentAmount) {
        // tslint:disable-next-line:radix
        const amount = parseInt(currentAmount) + 1;
        this.changeAmountProduct(code, amount);
    }
    decrementAmount(code, currentAmount) {
        if (currentAmount <= 1) {
            return;
        }
        const amount = currentAmount - 1;
        this.changeAmountProduct(code, amount);
    }
    saveDraftOrder() {
        if (this.note === undefined) {
            this.note = '';
        }
        if (this.address === undefined) {
            this.address = '';
        }
        if (!this.currentOrderId) {
            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const yyyy = today.getFullYear();
            const current = yyyy + mm + dd;
            this.isDisabledSave = true;
            const order = <CreateDraftOrderRequest>{
                factory_id: this.data.factory_id,
                code: Math.random().toString(),
                order_code: Math.random().toString(),
                creator_id: this.currentUserId,
                creator_note: this.note,
                type : this.type,
                distributor_id: this.currentUserDistributorId,
                deliver_address: this.address,
                products: [],
                items: []
            };
            // tslint:disable-next-line:forin
            for (const orderIndex in this.currentProductsSelected) {
                const newOrderProduct = {};
                newOrderProduct['product_id'] = this.currentProductsSelected[orderIndex].productId;
                newOrderProduct['amount'] = this.currentProductsSelected[orderIndex].amount;
                newOrderProduct['code'] = this.currentProductsSelected[orderIndex].code;
                newOrderProduct['user_note'] = this.currentProductsSelected[orderIndex].userNote;
                newOrderProduct['factory_id'] = this.currentProductsSelected[orderIndex].factoryId;
                newOrderProduct['grade_id'] = this.currentProductsSelected[orderIndex].gradeId;
                newOrderProduct['distributor_id'] = this.currentProductsSelected[orderIndex].distributorId;
                newOrderProduct['attributes'] = this.currentProductsSelected[orderIndex].attributeId;
                newOrderProduct['uom_id'] = this.currentProductsSelected[orderIndex].uomId;
                newOrderProduct['statusItem'] = this.currentProductsSelected[orderIndex].statusItem;
                newOrderProduct['dataCheckAmount'] = this.currentProductsSelected[orderIndex].dataCheckAmount;
                order.products.push(newOrderProduct);
                order.items.push(newOrderProduct);
            }

            this.store.dispatch(new CreateDraftOrder(order)).subscribe(success => {
                this.router.navigate(['order']);
                // setTimeout(() => {
                //     this.store.dispatch(new RemoveAllSelectedProductFromOrder({orderId: this.currentOrderId}));
                // }, 100);
            }, (error) => {
                this.isDisabledSave = false;
                const errorCreateOrder = error.toString().split('.');
                const convertArray = JSON.parse(errorCreateOrder[1]);
                if (convertArray.length > 0) {
                    // @ts-ignore
                    for (const i = 0; i < convertArray.length;  i++) {
                        $('#error-amount-' + convertArray[i].key).html(convertArray[i].message);
                    }
                }
            });
        }

    }
    cancelOrder() {
        const recoverOrder = <RecoverProductInOrderPayload> {
            orderId: this.currentOrderId
        };
        this.store.dispatch(new RecoverProductInOrder(recoverOrder));
    }
    submitConfirm( isFactory ) {
        // this.router.navigate(['order/add/'], { queryParams: {factory_id: isFactory}});
        this.isConfirm = true;
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
